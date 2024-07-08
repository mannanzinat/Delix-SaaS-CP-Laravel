<?php

namespace App\Repositories\Client;
use App\Models\Contact;
use App\Models\Message;
use App\Models\ChatRoom;
use App\Enums\MessageEnum;
use App\Traits\ImageTrait;
use App\Traits\RepoResponse;
use App\Traits\TelegramTrait;
use App\Traits\WhatsAppTrait;
use App\Enums\MessageStatusEnum;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class MessageRepository
{
    use ImageTrait, RepoResponse, TelegramTrait,WhatsAppTrait;

    private $model;

    private $contact;

    public function __construct(
        Message $model,
        Contact $contact,
    ) {
        $this->model   = $model;
        $this->contact = $contact;
    }

    public function all()
    {
        return Contact::latest()->withPermission()->paginate(setting('pagination'));
    }

    public function getChatContactList()
    {
        return $this->model->withPermission()->where('has_conversation', 1)->with(['conversation', 'country'])->orderBy('Me', 'DESC')->limit(50)->get();
    }

    public function sendTextMessage($request, $contact_id, $source, $conversation_id = null)
    {
        $message_type            = MessageEnum::TEXT->value;
        $message                 = new $this->model;
        $message->contact_id     = $contact_id;
        if ($conversation_id) {
            $message->conversation_id = $conversation_id;
        }
        $message->client_id      = Auth::user()->client_id;
        $message->value          = strip_tags($request->message);
        $message->message_type   = MessageEnum::TEXT;
        $message->is_contact_msg = 0;
        $message->source         = $source;
        $message->status         = MessageStatusEnum::SENT;
        $message->save();

        if ($source == 'whatsapp') {
            $this->sendWhatsAppMessage($message, $message_type);
        } elseif ($source == 'telegram') {
            $this->sendTelegramMessage($message, $message_type);
        }

        return $message;
    }

    public function sendDocumentMessage($request, $contact_id, $source, $conversation_id = null)
    {
        try {
            $message_type            = MessageEnum::DOCUMENT->value;
            $contact                 = $this->contact->findOrFail($contact_id);
            $message                 = new $this->model;
            $message->contact_id     = $contact->id;
            if ($conversation_id) {
                $message->conversation_id = $conversation_id;
            }
            $message->client_id      = Auth::user()->client_id;
            $file_info               = [];
            if ($request->hasFile('document')) {
                $file                     = $request->file('document');
                $fileExtension            = $file->getClientOriginalExtension();
                $file_info                = [
                    'name' => $request->file('document')->getClientOriginalName(),
                    'size' => round($request->file('document')->getSize() / 1024, 2),
                    'ext'  => $fileExtension,
                ];
                $media_url                = asset('public/'.$this->saveFile($request->document, $fileExtension, false));
                $message->header_document = $media_url;
            }
            $message->message_type   = MessageEnum::DOCUMENT;
            $message->file_info      = $file_info;
            $message->is_contact_msg = 0;
            $message->status         = MessageStatusEnum::SENT;
            $message->save();
            if ($source == 'whatsapp') {
                $this->sendWhatsAppMessage($message, $message_type);
            } elseif ($source == 'telegram') {
                $this->sendTelegramMessage($message, $message_type);
            }

            return $this->ajaxResponse(200, __('created_successfully'), '');
        } catch (\Throwable $e) {
            \Log::info('send Document Message', [$e->getMessage()]);
            return $this->ajaxResponse(200, __('an_unexpected_error_occurred_please_try_again_later.'), '');
        }
    }

    public function sendImageMessage($request, $contact_id, $source, $conversation_id = null)
    {
        try {
            $contact = $this->contact->findOrFail($contact_id);
            $message = new $this->model;
            if ($conversation_id) {
                $message->conversation_id = $conversation_id;
            }
            $file    = $request->file('image');
            if (! empty($file)) {
                $mimeType              = $file->getClientMimeType();
                $fileExtension         = $file->getClientOriginalExtension();
                switch ($mimeType) {
                    case strpos($mimeType, 'image') !== false:
                        $response              = $this->saveImage($file);
                        $mediaUrl              = getFileLink('original_image', $response['images']);
                        $messageType           = MessageEnum::IMAGE;
                        $message->header_image = $mediaUrl;
                        break;
                    case strpos($mimeType, 'audio') !== false:
                        $mediaUrl              = asset('public/'.$this->saveFile($file, $fileExtension, false));
                        $messageType           = MessageEnum::AUDIO;
                        $message->header_audio = $mediaUrl;
                        break;
                    case strpos($mimeType, 'video') !== false:
                        $mediaUrl              = asset('public/'.$this->saveFile($file, $fileExtension, false));
                        $messageType           = MessageEnum::VIDEO;
                        $message->header_video = $mediaUrl;
                        break;
                    default:
                        exit();
                        break;
                }
                $message->contact_id   = $contact->id;
                $message->client_id    = Auth::user()->client_id;
                $message->message_type = $messageType ?? null;
                $message->status       = MessageStatusEnum::SENDING;
                $message->save();
                if ($source == 'whatsapp') {
                    $response = $this->sendWhatsAppMessage($message, $messageType->value);
                } elseif ($source == 'telegram') {
                    $response = $this->sendTelegramMessage($message,  $messageType->value);
                }

                return $this->ajaxResponse(200, __('created_successfully'), '');
            } else {
                return $this->ajaxResponse(400, __('No file uploaded'), '');
            }
        } catch (\Throwable $e) {
            \Log::info('send Image Message', [$e->getMessage()]);
            return $this->ajaxResponse(500, __('an_unexpected_error_occurred_please_try_again_later.'), '');
        }
    }

    public function firstChatroom($user)
    {
        return ChatRoom::with('user', 'receiver', 'lastMessage')->whereHas('lastMessage')->where(function ($query) use ($user) {
            $query->where('user_id', $user->id)->orWhere('receiver_id', $user->id);
        })->latest()->first();
    }

    public function chatRoomExists($data)
    {
        return ChatRoom::where(function ($q) use ($data) {
            $q->where(function ($query) use ($data) {
                $query->where('user_id', $data['user_id'])->where('receiver_id', $data['receiver_id']);
            })->orWhere(function ($query) use ($data) {
                $query->where('user_id', $data['receiver_id'])->where('receiver_id', $data['user_id']);
            });
        })->latest()->first();
    }

    public function findChatroom($id): Model|Collection|Builder|array|null
    {
        return ChatRoom::with('user', 'receiver', 'lastMessage')->find($id);
    }

    public function chatRoomMessages($id, $type = null, $source = null): LengthAwarePaginator
    {
        return Message::where('contact_id', $id)->when($type == 'media', function ($query) {
            $query->where('header_image', '!=', '')->orWhere('header_video', '!=', '')->orWhere('header_audio', '!=', '');
        })->when($type == 'files', function ($query) {
            $query->where('header_document', '!=', '');
        })->when($type == 'links', function ($query) {
            $query->where('value', 'REGEXP', 'https?://[^ ]+');
        })
        ->withPermission()
        ->latest()
        // ->orderBy('id','DESC')
        ->paginate(1000);
    }

    public function messageUser($user, $data = []): LengthAwarePaginator
    {
        return ChatRoom::with('user', 'receiver', 'lastMessage')->whereHas('lastMessage')->where(function ($query) use ($user) {
            $query->where('user_id', $user->id)->orWhere('receiver_id', $user->id);
        })->when(arrayCheck('q', $data), function ($query) use ($data) {
            $query->whereHas('receiver', function ($q) use ($data) {
                $q->where(function ($q) use ($data) {
                    $q->where('name', 'like', '%'.$data['q'].'%')->orWhere('phone', 'like', '%'.$data['q'].'%');
                });
            });
        })->latest()->paginate(10);
    }

    public function createChatRoom($data)
    {
        return ChatRoom::create([
            'user_id'     => $data['user_id'],
            'receiver_id' => $data['receiver_id'],
            'is_accepted' => 1,
        ]);
    }

    public function find($id)
    {
        return $this->model->find($id);
    }


    

    public function clearChat($contact_id)
    {
        try {
            $messages = $this->model->where('contact_id', $contact_id)->withPermission()->get();
    
            foreach ($messages as $message) {
                if (!empty($message->header_image)) {
                    Storage::delete($message->header_image);
                }
                if (!empty($message->header_audio)) {
                    Storage::delete($message->header_audio);
                }
                if (!empty($message->header_video)) {
                    Storage::delete($message->header_video);
                }
                if (!empty($message->header_document)) {
                    Storage::delete($message->header_document);
                }
            }
            $this->model->where('contact_id', $contact_id)->delete();
            return $this->ajaxResponse(200, __('deleted_successfully'), '');
        } catch (\Throwable $e) {
            // Log the exception message for debugging purposes
            \Log::error('Error clearing chat: ' . $e->getMessage());
            return $this->ajaxResponse(500, __('an_unexpected_error_occurred_please_try_again_later.'), '');
        }
    }
    


}
