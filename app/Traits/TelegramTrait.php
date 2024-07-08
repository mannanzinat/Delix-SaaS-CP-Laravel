<?php

namespace App\Traits;

use Telegram\Bot\Api;
use App\Enums\TypeEnum;
use App\Models\BotGroup;
use App\Enums\StatusEnum;
use App\Enums\MessageEnum;
use App\Traits\CommonTrait;
use App\Models\ClientSetting;
use App\Models\GroupSubscriber;
use App\Enums\MessageStatusEnum;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Telegram\Bot\Laravel\Facades\Telegram;

trait TelegramTrait
{
    use CommonTrait;
    public $TELEGRAM_BASE_URL = 'https://api.telegram.org/';

    private function getClientSettingByToken($token)
    {
        return ClientSetting::where('access_token', $token)
            ->where('type', TypeEnum::TELEGRAM)
            ->active()
            ->with('client')
            ->first();
    }

    public function handleFile($fileId, $clientSetting)
    {
        $storage = setting('default_storage') ?: 'local';
        try {
            $getFileResponse = Telegram::getFile(['file_id' => $fileId]);
            if (isset($getFileResponse['file_path'])) {
                $filePath = $getFileResponse['file_path'];
                $fileUrl = $this->TELEGRAM_BASE_URL . 'file/bot' . config('telegram.bots.mybot.token') . '/' . $filePath;
                $responseImage = Http::withHeaders([])->get($fileUrl);
                if ($responseImage->successful()) {
                    $fileContents = $responseImage->getBody()->getContents();
                    if ($fileContents !== false) {
                        $fileExtension = '.' . pathinfo($filePath, PATHINFO_EXTENSION);
                        $fileName    = date('YmdHis') . '_original_' . rand(1, 500) . '.' . $fileExtension;
                        // Store file
                        if ($storage == 'wasabi') {
                            $filePath = "images/media/$fileName";
                            $path     = Storage::disk('wasabi')->put($filePath, $fileContents, 'public');
                            return Storage::disk('wasabi')->url($filePath);
                        } elseif ($storage == 's3') {
                            $filePath = "images/media/$fileName";
                            $path     = Storage::disk('s3')->put($filePath, $fileContents, 'public');
                            return Storage::disk('s3')->url($filePath);
                        } else {
                            $localDirectory = public_path("images/media/");
                            if (!file_exists($localDirectory)) {
                                mkdir($localDirectory, 0755, true);
                            }
                            $localPath      = "{$localDirectory}{$fileName}";
                            file_put_contents($localPath, $fileContents);
                            return url("public/images/media/$fileName");
                        }
                    } else {
                        Log::error('Failed to fetch file content');
                    }
                } else {
                    Log::error('Failed to fetch file from Telegram');
                }
            } else {
                Log::error('Telegram getFile method request failed');
            }
        } catch (\Exception $e) {
            Log::error('Exception occurred: ' . $e->getMessage());
        }
        return null;
    }

    private function processNewChatMembers($updates, $clientSetting)
    {
        if (isset($updates['message']['new_chat_members'])) {
            $groupId = $updates['message']['chat']['id'];
            $groupName = $updates['message']['chat']['title'];
            $supergroupSubscriberId = $groupId . '-' . $clientSetting->id;

            $existingGroup = BotGroup::where('supergroup_subscriber_id', $supergroupSubscriberId)->first();

            if (!$existingGroup) {
                $newGroup = new BotGroup();
                $newGroup->name = $groupName;
                $newGroup->group_id = $groupId;
                $newGroup->supergroup_subscriber_id = $supergroupSubscriberId;
                $newGroup->client_setting_id = $clientSetting->id;
                $newGroup->client_id = $clientSetting->client_id;
                $newGroup->type = TypeEnum::TELEGRAM;
                $newGroup->status = 1;
                $newGroup->is_admin = 1;
                $newGroup->save();
            }

            foreach ($updates['message']['new_chat_members'] as $newChatMember) {
                $id = $newChatMember['id'];
                $firstName = $newChatMember['first_name'];
                $lastName = $newChatMember['last_name'] ?? null;
                $username = $newChatMember['username'] ?? null;
                $isBot = $newChatMember['is_bot'];
                $groupSubscriberId =  $id . '-' . ($existingGroup ? $existingGroup->id : $newGroup->id);

                if (!$isBot) {
                    $avatar = null;
                    $telegram = new Api($clientSetting->access_token);
                    $response = $telegram->getUserProfilePhotos([
                        'user_id' => $id,
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
                    $groupSubscriber = new GroupSubscriber();
                    $groupSubscriber->name = $firstName . ' ' . $lastName;
                    $groupSubscriber->avatar = $avatar;
                    $groupSubscriber->username = $username;
                    $groupSubscriber->client_id = $clientSetting->client_id;
                    $groupSubscriber->type = TypeEnum::TELEGRAM;
                    $groupSubscriber->group_chat_id = $groupId;
                    $groupSubscriber->group_subscriber_id = $groupSubscriberId;
                    $groupSubscriber->group_id = $existingGroup ? $existingGroup->id : $newGroup->id;
                    $groupSubscriber->is_left_group = 0;
                    $groupSubscriber->status = 1;
                    $groupSubscriber->save();
                }
            }
        }
    }

    private function processLeftChatMember($updates, $clientSetting)
    {
        if (isset($updates['message']['left_chat_member'])) {
            $userId                 = $updates['message']['left_chat_member']['id'];
            $chatId                 = $updates['message']['chat']['id'];
            $supergroupSubscriberId = $userId . '-' . $clientSetting->id;
            $existingGroup          = BotGroup::where('supergroup_subscriber_id', $supergroupSubscriberId)->first();
            if ($existingGroup) {
                $groupSubscriberId  = $userId . '-' . $existingGroup->id;
                GroupSubscriber::where('group_subscriber_id', $groupSubscriberId)->update(['is_left_group' => 1, 'status' => 0]);
            }
        }
    }

    private function setWebhook($accessToken)
    {
        try {
            $webhookUrl = route('telegram.webhook', $accessToken);
            if (strpos($webhookUrl, 'http://') === 0) {
                $webhookUrl = 'https://' . substr($webhookUrl, 7);
            }
            $webhookResponse = Telegram::setWebhook(['url' => $webhookUrl]);
            return $webhookResponse;
        } catch (\Exception $e) {
            // dd($e->getMessage());
            return [];
        }
    }

    public function removeWebhook()
    {
        $response = Telegram::removeWebhook();
        return $response;
    }

    public function sendTelegramMessage($message, $type)
    {
        try {
            if (!empty($message->client) && !empty($message->client->telegramSetting->access_token)) {
                config(['telegram.bots.mybot.token' => $message->client->telegramSetting->access_token]);
            }
            $chatId     = $message->contact->group_chat_id;
            $botToken   = $message->client->telegramSetting->access_token;
            $telegram   = Telegram::bot('mybot');
            $response   = null;

            if ($message->message_type == MessageEnum::TEXT || $message->message_type == MessageEnum::TEXT->value) {
                $response = $this->sendMessage($botToken, $chatId, $message->value);
            } elseif ($message->message_type == MessageEnum::IMAGE || $message->message_type == MessageEnum::IMAGE->value) {
                $response = $this->sendMedia($botToken, $chatId, $message->header_image, 'photo');
            } elseif ($message->message_type == MessageEnum::AUDIO || $message->message_type == MessageEnum::AUDIO->value) {
                $response = $this->sendMedia($botToken, $chatId, $message->header_audio, 'audio');
            } elseif ($message->message_type == MessageEnum::VIDEO || $message->message_type == MessageEnum::VIDEO->value) {
                $response = $this->sendMedia($botToken, $chatId, $message->header_video, 'video');
            } elseif ($message->message_type == MessageEnum::DOCUMENT || $message->message_type == MessageEnum::DOCUMENT->value) {
                $response = $this->sendMedia($botToken, $chatId, $message->header_document, 'document');
            }
            if ($response && $response->successful()) {
                $messageId = $response->json('message_id');
                $message->message_id = $messageId;
                $message->status     = MessageStatusEnum::READ;
            } else {
                if ($response && $response->json('ok') === false) {
                    \Log::error('Telegram API Error', ['error' => $response->json()]);
                    $message->status = MessageStatusEnum::FAILED->value;
                }
            }

            $message->update();
            $campaign = $message->campaign;
            if ($campaign) {
                if ($message->status == MessageStatusEnum::READ) {
                    $campaign->total_sent += 1;
                    $campaign->total_delivered += 1;
                    $campaign->total_read += 1;
                } elseif ($message->status == MessageStatusEnum::FAILED) {
                    $campaign->total_failed += 1;
                }
                $campaign->save();
            }

            // Check if all child messages of the campaign are not scheduled
            if ($message->campaign && $message->campaign->messages) {
                $allmessages = $message->campaign->messages;
                $allNonScheduled = true;
                foreach ($allmessages as $conv) {
                    if ($conv->status == MessageStatusEnum::SCHEDULED) {
                        $allNonScheduled = false;
                        break;
                    }
                }
                // If all messages are not scheduled, update campaign status to COMPLETE
                if ($allNonScheduled) {
                    $message->campaign->update([
                        'status' => StatusEnum::EXECUTED
                    ]);
                }
            }
            $this->conversationUpdate($message->client_id, $message->contact_id);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    private function sendMessage($botToken, $chatId, $text)
    {
        $apiUrl = "https://api.telegram.org/bot{$botToken}/sendMessage";
        return Http::post($apiUrl, [
            'chat_id' => $chatId,
            'text' => $text,
        ]);
    }

    private function sendMedia($botToken, $chatId, $filePath, $mediaType)
    {
        $fileName = basename($filePath);
        $apiUrl = "https://api.telegram.org/bot{$botToken}/send{$mediaType}";

        return Http::attach(
            $mediaType,
            file_get_contents($filePath),
            $fileName
        )->post($apiUrl, [
            'chat_id' => $chatId,
        ]);
    }

    public function sendAudio($bot_token, $group_id, $userID)
    {
        $url = $this->TELEGRAM_BASE_URL . "/bot{$bot_token}/getChatMember";
        $response = Http::post($url, [
            'chat_id' => $group_id,
            'user_id' => $userID
        ]);
        if ($response->successful()) {
            return $response->json();
        } else {
            return null;
        }
    }

    public function checkGroupAdmin($bot_token, $group_id, $userID)
    {
        $url = $this->TELEGRAM_BASE_URL . "/bot{$bot_token}/getChatMember";
        $response = Http::post($url, [
            'chat_id' => $group_id,
            'user_id' => $userID
        ]);
        if ($response->successful()) {
            return $response->json();
        } else {
            return null; // or throw an exception, log error, etc.
        }
    }
    public function getGroupInfo($bot_token)
    {
        $telegram = new Api($bot_token);
        try {
            $chatInfo = $telegram->getChat(['chat_id' => $bot_token]);
            if ($chatInfo->isOk()) {
                $groupId = $chatInfo->getId();
                $groupName = $chatInfo->getTitle();
                $groupType = $chatInfo->getType();
                return [
                    'id' => $groupId,
                    'name' => $groupName,
                    'type' => $groupType
                ];
            } else {
                return null;
            }
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getSubscriberPhoto($botToken, $user_id)
    {
        $clientSetting = null;
        try {
            $telegram = new Api($botToken);
            $response = $telegram->getUserProfilePhotos([
                'user_id' => $user_id,
            ]);
            $responseData = json_decode($response, true);
            if ($responseData !== null && isset($responseData['photos'])) {
                $photos = $responseData['photos'];
                if (!empty($photos)) {
                    $firstPhoto = $photos[0][0];
                    $fileId = $firstPhoto['file_id'];
                    $avatar =  $this->handleFile($fileId, $clientSetting);
                    \Log::info('$avatar', [$avatar]);

                    return $avatar;
                }
            }
            return null;
        } catch (\Exception $e) {
            Log::info('Profile photo Exception: ', [$e->getMessage()]);
            return null;
        }
    }
}
