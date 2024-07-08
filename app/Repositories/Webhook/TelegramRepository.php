<?php
namespace App\Repositories\Webhook;
use Telegram\Bot\Api;
use App\Models\Client;
use App\Enums\TypeEnum;
use App\Models\Contact;
use App\Models\Country;
use App\Models\Message;
use App\Models\BotGroup;
use App\Enums\MessageEnum;
use Illuminate\Http\Request;
use App\Traits\BotReplyTrait;
use App\Traits\TelegramTrait;
use App\Traits\WhatsAppTrait;
use App\Models\OneSignalToken;
use App\Models\GroupSubscriber;
use App\Enums\MessageStatusEnum;
use App\Services\WhatsAppService;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramRepository
{
    const TELEGRAM_BASE_URL = 'https://api.telegram.org/';
    use TelegramTrait, WhatsAppTrait,BotReplyTrait;
    private $clientModel;
    private $country;
    private $contact;
    private $message;
    protected $whatsappService;
    public function __construct(
        Client $clientModel,
        Country $country,
        Contact $contact,
        Message $message,
        WhatsAppService $whatsappService
    ) {
        $this->clientModel     = $clientModel;
        $this->contact         = $contact;
        $this->message    = $message;
        $this->whatsappService = $whatsappService;
        $this->country         = $country;
    }

    public function receiveResponse(Request $request, $token)
    {
        $clientSetting = $this->getClientSettingByToken($token);
        if (empty($clientSetting) || empty($clientSetting->client)) {
            return response()->json(['status' => 'success']);
        }
        config(['telegram.bots.mybot.token' => $clientSetting->access_token]);
        try {
            $updates = Telegram::commandsHandler(true);
            $this->processNewChatMembers($updates, $clientSetting);
            $this->processLeftChatMember($updates, $clientSetting);
            $this->processMessage($updates, $clientSetting);
            return response()->json(['status' => 'success']);
        } catch (\Throwable $e) {
            return response()->json(['send' => false, 'error' => __('an_unexpected_error_occurred_please_try_again_later.'), 'data' => $request]);
        }
    }

    private function processMessage($updates, $clientSetting)
    {
        try {
            $message_response   = $updates['message'];
            $from      = $message_response['from'];
            $contactId = $from['id'];
            $firstName = $from['first_name'];
            $lastName  = $from['last_name'] ?? null;
            $username  = $from['username']  ?? null;
            $groupId   = $message_response['chat']['id'];
            $groupName = $message_response['chat']['title'];
            if (isset($message_response['text']) || isset($message_response['photo']) || isset($message_response['audio']) || isset($message_response['video']) || isset($message_response['document']) || isset($message_response['location'])) {
                $supergroupSubscriberId            = $groupId . '-' . $clientSetting->id;
                $existingGroup                     = BotGroup::where('supergroup_subscriber_id', $supergroupSubscriberId)->first();
                if (!$existingGroup) {
                    $newGroup                           = new BotGroup();
                    $newGroup->name                     = $groupName;
                    $newGroup->group_id                 = $groupId;
                    $newGroup->supergroup_subscriber_id = $supergroupSubscriberId;
                    $newGroup->client_setting_id        = $clientSetting->id; ///Bot ID
                    $newGroup->client_id                = $clientSetting->client_id;
                    $newGroup->type                     = TypeEnum::TELEGRAM;
                    $newGroup->is_admin                 = 1;
                    $newGroup->save();
                }
                $existingContact                   = Contact::where('group_chat_id', $groupId)->first();
                if (!$existingContact) {
                    $newContact                = new Contact();
                    $newContact->name          = $groupName;
                    $newContact->username      = $groupName;
                    $newContact->group_chat_id = $groupId;
                    $newContact->group_id      = $existingGroup ? $existingGroup->id : $newGroup->id;
                    $newContact->client_id     = $clientSetting->client_id;
                    $newContact->type          = TypeEnum::TELEGRAM;
                    $newContact->save();
                }
                $groupSubscriberId                 = $contactId . '-' . ($existingGroup ? $existingGroup->id : $newGroup->id);
                $existingSubscriber                = GroupSubscriber::where('group_subscriber_id', $groupSubscriberId)->first();
                // \Log::info('$existingSubscriber: ', [$existingSubscriber]);
                if (empty($existingSubscriber)) {
                    $avatar = null;
                    $telegram = new Api($clientSetting->access_token);
                    $response = $telegram->getUserProfilePhotos([
                        'user_id' => $contactId,
                    ]);
                    $responseData = json_decode($response, true);
                    if ($responseData !== null && isset($responseData['photos'])) {
                        $photos = $responseData['photos'];
                        if (!empty($photos)) {
                            $firstPhoto = $photos[0][0];
                            $fileId = $firstPhoto['file_id'];
                            $avatar =  $this->handleFile($fileId, $clientSetting);
                        }
                    }
                    $newSubscriber                      = new GroupSubscriber();
                    $newSubscriber->name                = trim($firstName . ' ' . $lastName);
                    $newSubscriber->username            = $username;
                    $newSubscriber->avatar              = $avatar;
                    $newSubscriber->type                = TypeEnum::TELEGRAM;
                    $newSubscriber->client_id           = $clientSetting->client_id;
                    $newSubscriber->group_subscriber_id = $groupSubscriberId;
                    $newSubscriber->group_id            = $existingGroup ? $existingGroup->id : $newGroup->id;
                    $newSubscriber->group_chat_id       = $groupId;
                    $newSubscriber->save();
                }
                $contact                      = $existingContact ?: $newContact;
                $message                      = new Message();
                $message->message_id          = $message_response['message_id'];
                $message->contact_id          = $contact->id;
                $message->group_subscriber_id = $existingSubscriber ? $existingSubscriber->id : $newSubscriber->id;
                $message->client_id           = $clientSetting->client_id;
                $message->source              = TypeEnum::TELEGRAM;
                $notified_message                  = '';
                if (isset($message_response['text'])) {
                    $message->value        = $message_response['text'];
                    $message->message_type = MessageEnum::TEXT;
                    $notified_message      = $message_response['text'];
                }
                if (isset($message_response['photo']) && isset($message_response['photo'][0]['file_id'])) {
                    $message->header_image = $this->handleFile($message_response['photo'][1]['file_id'], $clientSetting);
                    $message->message_type = MessageEnum::IMAGE;
                    $notified_message           = __('sent_an_image');
                }
                if (isset($message_response['audio']) && isset($message_response['audio']['file_id'])) {
                    $message->header_audio = $this->handleFile($message_response['audio']['file_id'], $clientSetting);
                    $message->message_type = MessageEnum::AUDIO;
                    $notified_message           = __('sent_an_audio_file');
                }
                if (isset($message_response['video']) && isset($message_response['video']['file_id'])) {
                    $message->header_video = $this->handleFile($message_response['video']['file_id'], $clientSetting);
                    $message->message_type = MessageEnum::VIDEO;
                    $notified_message      = __('sent_a_video');
                }
                if (isset($message_response['document']) && isset($message_response['document']['file_id'])) {
                    $message->header_document = $this->handleFile($message_response['document']['file_id'], $clientSetting);
                    $message->message_type    = MessageEnum::DOCUMENT;
                    $notified_message       = __('shared_a_document_with_you');
                }
                if (isset($message_response['location'])) {
                    $latitude                      = $message_response['location']['latitude'];
                    $longitude                     = $message_response['location']['longitude'];
                    $locationUrl                   = 'https://www.google.com/maps?q=' . $latitude . ',' . $longitude;
                    $message->header_location = $locationUrl;
                    $notified_message              = __('shared_a_location_with_you');
                }
                $message->status              = MessageStatusEnum::DELIVERED;
                $message->is_contact_msg      = 1;  
                $message->save();   
                if (setting('is_pusher_notification_active')) {
                    \Log::error('is_pusher_notification_active');     
                    \Log::error($clientSetting->client);         
                    event(new \App\Events\ReceiveUpcomingMessage($clientSetting->client));
                }
                if (setting('is_onesignal_active')) {
                    $this->pushNotification([
                        'ids'     => OneSignalToken::where('client_id', $clientSetting->client->id)->pluck('subscription_id')->toArray(),
                        'message' => $notified_message,
                        'heading' => $contact->name,
                        'url'     => route('client.chat.index', ['contact' => $contact->id]),
                    ]);
                }
                $contact->update(['last_conversation_at' => now(),'has_conversation' => 1,'has_unread_conversation' => 1]);
            }
        } catch (\Exception $e) {
            // \Log::error('Exception occurred: ' . $e->getMessage());
        }
    }

}
