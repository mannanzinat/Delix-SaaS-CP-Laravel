<?php
namespace App\Traits;
use App\Models\Client;
use App\Models\Message;
use App\Enums\StatusEnum;
use App\Traits\CommonTrait;
use App\Enums\MessageStatusEnum;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Netflie\WhatsAppCloudApi\WhatsAppCloudApi;
use Netflie\WhatsAppCloudApi\Message\Media\LinkID;
use Netflie\WhatsAppCloudApi\Message\Template\Component;

trait WhatsAppTrait
{
    use SendNotification, CommonTrait;

    protected function sendWAOtp($to, $otp)
    {
        try{
            $facebook_api       = 'https://graph.facebook.com/v19.0/';
            $response           = Http::withToken(setting('access_token'))
                                  ->post($facebook_api . setting('phone_number_id') . "/messages", [
                                    'messaging_product' => 'whatsapp',
                                    'to'                => $to,
                                    'type'              => 'template',
                                    'template'          => [
                                        'name'          => 'delix_signup_otp',
                                        'language'      => [
                                            'code'      => 'en'
                                        ],
                                        'components'    => [
                                            [
                                                'type'          => 'body',
                                                'parameters'    => [
                                                    [
                                                        'type'  => 'text',
                                                        'text'  => $otp
                                                    ]
                                                ]
                                            ],

                                            [
                                                'type'          => 'button',
                                                'sub_type'      => 'URL',
                                                'index'         => 0,
                                                'parameters'    => [
                                                    [
                                                        'type'  => 'text',
                                                        'text'  => $otp
                                                    ],
                                                ]
                                            ]
                                        ]
                                    ]
                                ]);
            return $response->json();

        }catch(\Exception $e){
            dd($e->getMessage());
        }
    }

    private function sendWhatsAppCampaignMessage($message)
    {
        $client = Client::find($message->client_id);
        try {
            $accessToken             = getClientWhatsAppAccessToken($client);
            $whatspp_phone_number_id = getClientWhatsAppPhoneID($client);
            $template                = $message->campaign->template ?? $message->template;
            if (!empty($template)) {
                $contact                 = $message->contact;
                if ($message->contact->status == 1) {
                    $whatsapp_cloud_api = new WhatsAppCloudApi([
                        'from_phone_number_id' => $whatspp_phone_number_id,
                        'access_token'         => $accessToken,
                    ]);
                    $component_header   = json_decode($message->component_header)  ?? [];
                    $component_body     = json_decode($message->component_body)    ?? [];
                    $component_buttons  = json_decode($message->component_buttons) ?? [];
                    $components         = new Component($component_header, $component_body, $component_buttons);
                    $message_api        = $whatsapp_cloud_api->sendTemplate($contact->phone, $template->name, $template->language, $components);
                    $message_body       = json_decode($message_api->body(), true);
                    if (!empty($message_body['messages'])) {
                        $message->message_id = $message_body['messages'][0]['id'];
                        $message->status     = MessageStatusEnum::SENT;
                        $message->update();
                    } else {
                        $message->error  = isset($message_body['error']) ? $message_body['error']['message'] : 'Unknown';
                        $message->status = MessageStatusEnum::FAILED;
                        $message->update();
                    }
                }
                if ($message->campaign) {
                    $campaign = $message->campaign;
                    $campaignMessages = $campaign->messages();
                    if ($campaignMessages->count() == 1) {
                        DB::table('campaigns')->where('id', $campaign->id)->update([
                            'status' => StatusEnum::PROCESSED
                        ]);
                    }
                }
                return true;
            } else {
                $message->error  = 'Template is empty';
                $message->status = MessageStatusEnum::FAILED;
                $message->update();
                return false;
            }
        } catch (\Exception $e) {
            if ($message->campaign) {
                $campaign = $message->campaign;
                DB::table('campaigns')->where('id', $campaign->id)->update([
                    'status' => StatusEnum::PROCESSED
                ]);
            }
            Log::error($e->getMessage());
            $errorMessage = isset(json_decode($e->getMessage(), true)['error']['message']) ? json_decode($e->getMessage(), true)['error']['message'] : 'Unknown';
            $message->error = $errorMessage;
            $message->status = MessageStatusEnum::FAILED;
            $message->save();
            return false;
        }
    }

    public function sendWhatsAppMessage($message, $message_type)
    {
        Log::info('sendWhatsAppMessage called : ', [1]);
        Log::info('send WhatsApp Message: ', [$message]);

        $client = Client::find($message->client_id);
        try {
            $accessToken = getClientWhatsAppAccessToken($client);
            $whatsapp_phone_number_id = getClientWhatsAppPhoneID($client);
            $contact = $message->contact;
            $whatsapp_cloud_api = new WhatsAppCloudApi([
                'from_phone_number_id' => $whatsapp_phone_number_id,
                'access_token' => $accessToken,
            ]);
            if ($message_type == 'text') {
                Log::error($message_type);
                $response = $whatsapp_cloud_api->sendTextMessage($contact->phone, $message->value);
            } elseif ($message_type == 'image') {
                Log::info($message_type);
                $link_id = new LinkID($message->header_image);
                $response = $whatsapp_cloud_api->sendImage($contact->phone, $link_id);
            } elseif ($message_type == 'audio') {
                Log::error($message_type);
                $link_id = new LinkID($message->header_audio);
                $response = $whatsapp_cloud_api->sendAudio($contact->phone, $link_id);
            } elseif ($message_type == 'video') {
                Log::error($message_type);
                $caption = '';
                $link_id = new LinkID($message->header_video);
                $response = $whatsapp_cloud_api->sendVideo($contact->phone, $link_id, $caption);
            } elseif ($message_type == 'document') {
                Log::error($message_type);
                $document_name = basename($message->header_document);
                $document_caption = '';
                $document_link = $message->header_document;
                $link_id = new LinkID($document_link);
                $response = $whatsapp_cloud_api->sendDocument($contact->phone, $link_id, $document_name, $document_caption);
            }

            $message_body = json_decode($response->body(), true);
            if (!empty($message_body['messages'])) {
                $message->message_id = $message_body['messages'][0]['id'];
                $message->status = MessageStatusEnum::SENT;
            } else {
                $message->error = isset($message_body['error']) ? $message_body['error']['message'] : 'Unknown';
                $message->status = MessageStatusEnum::FAILED;
                // $this->conversationUpdate($message->client_id, $message->contact_id);
                // return true;
            }
            $message->update();

            $this->conversationUpdate($message->client_id, $message->contact_id);
            return true;
        } catch (\Exception $e) {
            // dd($e->getMessage());
            Log::error($e->getMessage());
            $errorMessage = isset(json_decode($e->getMessage(), true)['error']['message']) ? json_decode($e->getMessage(), true)['error']['message'] : 'Unknown';
            $message->error = $errorMessage;
            $message->status = MessageStatusEnum::FAILED;
            $message->save();
            // dd($message);
            return false;
        }
    }



    public function handleReceivedMedia($client, $media_id, $fileExtension = '.jpg')
    {
        $storage = setting('default_storage') != '' || setting('default_storage') != null ? setting('default_storage') : 'local';
        $url = $this->facebook_api . $media_id;
        $accessToken = getClientWhatsAppAccessToken($client);

        // Log the URL and access token for debugging
        Log::info('Request URL', ['url' => $url]);
        Log::info('Access Token', ['accessToken' => $accessToken]);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ])->withoutVerifying()->get($url);

            // Log the full response for debugging
            Log::info('Response Status', ['status' => $response->status()]);
            Log::info('Response Headers', ['headers' => $response->headers()]);
            Log::info('Response Body', ['body' => $response->body()]);

            $content = json_decode($response->body(), true);

            // Check if the response content is valid
            if (!$content || !isset($content['url'])) {
                Log::error('Invalid response content', ['content' => $content]);
                throw new \Exception('Invalid response content');
            }

            $responseImage = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
            ])->withoutVerifying()->get($content['url']);

            Log::info('Response Image Status', ['status' => $responseImage->status()]);
            Log::info('Response Image Headers', ['headers' => $responseImage->headers()]);
            Log::info('Response Image Body', ['body' => $responseImage->body()]);

            $fileContents = $responseImage->getBody()->getContents();

            if ($fileContents === false) {
                Log::error('Error downloading and storing media');
                throw new \Exception('Error downloading image');
            }

            if ($storage == 'wasabi') {
                $fileName = "images/media/{$content['id']}{$fileExtension}";
                $path = Storage::disk('wasabi')->put($fileName, $fileContents, 'public');
                return Storage::disk('wasabi')->url($fileName);
            } elseif ($storage == 's3') {
                $fileName = "images/media/{$content['id']}{$fileExtension}";
                $path = Storage::disk('s3')->put($fileName, $fileContents, 'public');
                return Storage::disk('s3')->url($fileName);
            } else {
                $directory = public_path('images/media');
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true); // Create the directory if it doesn't exist
                }

                $fileName = "{$content['id']}{$fileExtension}";
                $filePath = "{$directory}/{$fileName}";

                file_put_contents($filePath, $fileContents);

                return asset("public/images/media/{$fileName}");
            }
        } catch (\Exception $e) {
            Log::error('Error downloading and storing media: ' . $e->getMessage());
            return null;
        }
    }


    private function determineNextNode($flow, $current_node, $user_response)
    {
        $edge = $flow->edges->where('source', $current_node->node_id);

        if ($current_node->type == 'box-with-condition' && $user_response == 0) {
            $edge = $edge->where('sourceHandle', 'false')->first();
        } else {
            $edge = $edge->first();
        }

        if ($edge) {
            return $flow->nodes->firstWhere('node_id', $edge->target);
        }

        return null; // Or handle this case as needed
    }

    private function prepareNodeResponse($node): array
    {
        return [
            'node_id' => $node->node_id,
            'type'    => $node->type,
            'data'    => $node->data,
        ];
    }
}
