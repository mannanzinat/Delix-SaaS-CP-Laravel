<?php
namespace App\Repositories\Webhook;
use App\Models\Flow;
use App\Models\Contact;
use App\Models\Country;
use App\Models\Message;
use App\Models\Segment;
use App\Enums\StatusEnum;
use App\Models\Client;    
use App\Models\ContactsList;
use App\Models\ClientSetting;
use App\Traits\BotReplyTrait;
use App\Traits\WhatsAppTrait;
use App\Models\OneSignalToken;
use App\Enums\MessageStatusEnum;
use App\Services\WhatsAppService;
use App\Models\ContactRelationList;
use Illuminate\Support\Facades\Log;
use App\Models\ContactRelationSegments;
use Netflie\WhatsAppCloudApi\WhatsAppCloudApi;

class WhatsappRepository
{
    use WhatsAppTrait,BotReplyTrait;
    private $clientModel;
    private $country;
    private $contact;
    private $message;
    private $flow;
    protected $whatsappService;

    public function __construct(
        Client $clientModel,
        Country $country,
        Contact $contact,
        Message $message,
        Flow $flow,
        WhatsAppService $whatsappService
    ) {
        $this->clientModel = $clientModel;
        $this->contact = $contact;
        $this->message = $message;
        $this->whatsappService = $whatsappService;
        $this->country = $country;
        $this->flow = $flow;
    }

    public function verifyToken($request, $token)
    {
        $hubMode = $request->hub_mode;
        $hubVerifyToken = $request->hub_verify_token;
        $hubChallenge = $request->hub_challenge;
        $client = $this->clientModel->where('webhook_verify_token', $hubVerifyToken)->with('whatsappSetting')->first();
        if (!empty($client) && !empty($client->webhook_verify_token)) {
            if ($hubMode && $hubMode === 'subscribe') {
                if (!empty($client->whatsappSetting)) {
                    $whatsappSetting = $client->whatsappSetting;
                } else {
                    $whatsappSetting = new ClientSetting();
                    $whatsappSetting->client_id = $client->id;
                    $whatsappSetting->save();
                    $client->load('whatsappSetting');
                }
                $whatsappSetting->webhook_verified = 1;
                $whatsappSetting->save();
                return response($hubChallenge, 200)->header('Content-Type', 'text/plain');
            } else {
                return response()->json([], 403);
            }
        } else {
            return response()->json([], 403);
        }
    }



    public function receiveResponse($request, $token)
    {
        Log::info('receiveResponse', [$request]);

        $client = $this->clientModel->where('webhook_verify_token', $token)->with('whatsappSetting')->first();
        if (!empty($client) && !empty($client->webhook_verify_token)) {
            try {
                $value = $request->entry[0]['changes'][0]['value'];
                Log::info('$value', [$value]);
                if (isset($value['statuses'])) {
                    $this->handleStatusUpdate($value);
                } else {
                    $this->handleIncomingMessage($value, $client);
                }
                return response()->json(['send' => true]);
            } catch (\Throwable $e) {
                Log::info('Throwable', [$e->getMessage()]);
                return response()->json(['send' => false, 'error' => __('an_unexpected_error_occurred_please_try_again_later.'), 'data' => $request]);
            }
        } else {
            return response()->json(['send' => false]);
        }
    }

    private function handleStatusUpdate($value)
    {
        try {
            $incomming_status = $value['statuses'][0]['status'];
            $message_id = $value['statuses'][0]['id'];
            $message = $this->message->where('message_id', $message_id)->first();
            if ($message) {
                // Log::info('Statuses ', [$value['statuses']]);
                $message->status = $incomming_status;
                $message->error = isset($value['statuses'][0]['errors'][0]['message']) ? $value['statuses'][0]['errors'][0]['message'] : '';
                $message->update();
                $campaign = $message->campaign;
                if (!empty($campaign)) {
                    // Check if the status is 'failed' and there's an error code
                    if ($incomming_status === 'failed' && isset($value['statuses'][0]['errors'][0]['code'])) {
                        $error_code = $value['statuses'][0]['errors'][0]['code'];
                        // Check if the error code requires stopping the campaign
                        if ($this->isErrorStoppingCampaign($error_code)) {
                            $campaign->status = StatusEnum::STOPPED;
                            $campaign->errors = $this->getErrorMessage($error_code);
                            $campaign->update();
                        }
                    }

                    switch ($incomming_status) {
                        case 'delivered':
                            if ($message->status != 'read') {
                                $campaign->total_delivered += 1;
                            }
                            break;
                        case 'sent':
                            if ($message->status != 'delivered') {
                                $campaign->total_sent += 1;
                            }
                            break;
                        case 'read':
                            $campaign->total_read += 1;
                            break;
                        case 'failed':
                            $campaign->total_failed += 1;
                            break;
                    }
                    $campaign->save();
                }
            }
        } catch (\Exception $e) {
            Log::info('$campaign ', [$e->getMessage()]);
            return false;
        }
    }

    private function getErrorMessage($error_code)
    {
        $whatsapp_error = config('static_array.whatsapp_error');
        $index = array_search($error_code, array_column($whatsapp_error, 'code'));
        $description = $index !== false ? $whatsapp_error[$index]['description'] : 'Unknown Error';
        return $description;
    }

    private function isErrorStoppingCampaign($error_code)
    {
        $stop_campaign_errors = config('static_array.stop_campaign_errors');
        return in_array($error_code, $stop_campaign_errors);
    }

    private function handleIncomingMessage($value, $client)
    {
        try {
        $phone = $value['messages'][0]['from'];
        $type = $value['messages'][0]['type'];
        $name = $value['contacts'][0]['profile']['name'];
        $message_id = $value['messages'][0]['id'];
        Log::info('$name ', [$name]);
        $contact =  $this->contact->where('client_id', $client->id)->where('phone', $phone)->orWhere('phone', "+" . $phone)->first();
        if (!$contact) {
            $contact = new Contact();
            $contact->name = $name;
            $contact->phone = $phone;
            $contact->client_id = $client->id;
            $contact->country_id = $this->whatsappService->extractCountryCode($phone);
            $contact->has_conversation = 1;
            $contact->is_verified = 1;
            $contact->has_unread_conversation = 1;
            $contact->last_conversation_at = now();
            $contact->status = 1;
            $contact->save();
            Log::info('$contact ', [$contact]);

            $contactList =  ContactsList::where('client_id', $client->id)->where('name', 'Uncategorized')->first();
            if (empty($contactList)) {
                $contactList = new ContactsList();
                $contactList->name = 'Uncategorized';
                $contactList->client_id = $client->id;
                $contactList->save();
            }

            ContactRelationList::firstOrCreate([
                'contact_id' => $contact->id,
                'contact_list_id' => $contactList->id,
            ]);
            $defaultSegment = Segment::firstOrCreate([
                'client_id' => $client->id,
                'title' => 'Default',
            ], [
                'client_id' => $client->id,
                'title' => 'Default',
            ]);
            // Now, create the relation with the Default segment
            ContactRelationSegments::firstOrCreate([
                'contact_id' => $contact->id,
                'segment_id' => $defaultSegment->id,
            ]);

        } else {
            $contact->update([
                'is_verified' => 1,
                'has_conversation' => 1,
                'has_unread_conversation' => 1,
                'last_conversation_at' => now(),
            ]);
        }
        $content = $value;
        $is_contact_msg = true;
        $is_campaign_msg = false;
        $this->saveIncommingMessage($contact, $content, $client, $is_contact_msg, $is_campaign_msg, $type, $message_id);
        } catch (\Exception $e) {
            Log::info('handleIncomingMessage ', [$e->getMessage()]);
            return false;
        }
    }

    private function saveIncommingMessage($contact, $content, $client, $is_contact_msg, $is_campaign_msg, $type, $message_id)
    {
        $accessToken             = getClientWhatsAppAccessToken($client);
        $whatspp_phone_number_id = getClientWhatsAppPhoneID($client);
        try {
            $message                  = new Message();
            $message->contact_id      = $contact->id;
            $message->message_id      = $message_id;
            $message->client_id       = $client->id;   
            $notified_message              = '';
            if ($type == 'image') {
                $response                   = $this->handleReceivedMedia($client, $content['messages'][0]['image']['id'], '.jpg');
                $message->header_image = $response;
                $notified_message           =__('sent_an_image');
            } elseif ($type == 'audio') {
                $response                   = $this->handleReceivedMedia($client, $content['messages'][0]['audio']['id'], '.mp3');
                $message->header_audio = $response;
                $notified_message           = __('sent_an_audio_file');
            } elseif ($type == 'video') {
                $response                   = $this->handleReceivedMedia($client, $content['messages'][0]['video']['id'], '.mp4');
                $message->header_video = $response;
                $notified_message           = __('sent_a_video');
            } elseif ($type == 'text') {
                $response             = $content['messages'][0]['text']['body'];
                $message->value = $response;
                $notified_message    = $response;
            } elseif ($type == 'contacts' || $type == 'contact') {
                $message->contacts = json_encode($content['messages'][0]['contacts']);
                $notified_message       = __('shared_a_contact_with_you');
            } elseif ($type == 'document') {
                $response                      = $this->handleReceivedMedia($client, $content['messages'][0]['document']['id'], '.pdf');
                $message->header_document = $response;
                $notified_message              = __('shared_a_document_with_you');
            } elseif ($type == 'location') {
                $response                       = 'https://www.google.com/maps?q=' . $content['messages'][0]['location']['latitude'] . ',' . $content['messages'][0]['location']['longitude'];
                $message->header_location = $response;
                $notified_message = __('shared_a_location_with_you');
            } elseif ($type == 'button') {
                $buttonTexts = [];
                $buttonTexts[] = $content['messages'][0]['type'];
                $buttonTexts[] = $content['messages'][0]['button'];
                $message->buttons       = json_encode($buttonTexts);
                $notified_message      = $content['messages'][0]['button']['text'];
            }
            $message->message_type    = $type;
            $message->components      = null;
            $message->campaign_id     = null;
            $message->is_contact_msg  = $is_contact_msg;
            $message->is_campaign_msg = $is_campaign_msg;
            $message->status          = MessageStatusEnum::DELIVERED;
            $message->save();
            Log::info('message ', [$message]);
            $message->status          = MessageStatusEnum::DELIVERED;
            $message->update();
            if (setting('is_pusher_notification_active')) {
                event(new \App\Events\ReceiveUpcomingMessage($client));
            }
            if (setting('is_onesignal_active')) {
                $this->pushNotification([
                    'ids'     => OneSignalToken::where('client_id', $client->id)->pluck('subscription_id')->toArray(),
                    'message' => $notified_message,
                    'heading' => $contact->name,
                    'url'     => route('client.chat.index', ['contact' => $contact->id]),
                ]);
            }

            $contact->update(['last_conversation_at' => now(), 'has_conversation' => 1, 'has_unread_conversation' => 1]);

            if ($message) {
                $this->QuickReply($message);
            }
            
            // $this->conversationUpdate($client->id, $contact->id);
            return true;
        } catch (\Exception $e) {
            Log::error('Exception', [$e->getMessage()]);
            return false;
        }
    }

    
  



}
