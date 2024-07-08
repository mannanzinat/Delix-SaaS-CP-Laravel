<?php
namespace App\Traits;
use App\Models\Flow;
use App\Models\Client;
use App\Models\Contact;
use App\Models\Message;
use App\Models\BotReply;
use App\Enums\MessageEnum;
use App\Enums\BotReplyType;
use App\Traits\CommonTrait;
use Orhanerday\OpenAi\OpenAi;
use App\Enums\MessageStatusEnum;
use Illuminate\Support\Facades\Log;

trait BotReplyTrait
{
    use CommonTrait;

    public $facebook_api = 'https://graph.facebook.com/v19.0/';

    public function QuickReply($message)
    {
        try {
            $contact = Contact::where('id', $message->contact_id)->first();
            $client = Client::where('id', $message->client_id)->first();
            $conversation_text = strtolower($message->value);
            $flow = $contact->flow; // Assuming a relationship is defined in the Contact model
            $message_text = '';
            $keywords = '';
            $flow_id = $flow->id ?? 1; // Use the retrieved flow's ID
            $node_id = '';
            $user_response = '';

            return $this->FlowReply($message, $flow_id, $node_id, $user_response);
           
            if ($message_text) {
                $pattern = '/{{\s*([^}]+)\s*}}/';
                preg_match_all($pattern, $message_text, $matches);
                $variables = $matches[1];
                foreach ($variables as $variable) {
                    switch ($variable) {
                        case 'name':
                            $message_text = str_replace('{{' . $variable . '}}', $contact->name, $message_text);
                            break;
                        case 'phone':
                            $message_text = str_replace('{{' . $variable . '}}', $contact->phone, $message_text);
                            break;
                    }
                }

                $reply_message = new Message();
                $reply_message->components = null;
                $reply_message->campaign_id = null;
                $reply_message->contact_id = $contact->id;
                $reply_message->client_id = $client->id;
                $reply_message->value = $message_text;
                $reply_message->message_type = MessageEnum::TEXT;
                $reply_message->status = MessageStatusEnum::SENDING;
                $reply_message->save();
                Log::info('reply_message ', [$reply_message]);
            }
            $this->sendWhatsAppMessage($reply_message, 'text');
            return true;
        } catch (\Exception $e) {
            dd($e->getMessage());
            Log::info('send Bot Reply Message', [$e->getMessage()]);
            return false;
        }
    }

    public function BOTReply($conversation_text, $client)
    {
        $message_text = '';
        $bot_replies = BotReply::where('client_id', $conversation_text->client_id)->where('status', 1)->get();
        foreach ($bot_replies as $reply) {
            $keywords = explode(',', $reply->keywords);

            foreach ($keywords as $keyword) {

                $keyword = trim($keyword);
                if ($reply->reply_type == BotReplyType::EXACT_MATCH && $conversation_text === $keyword) {
                    if ($reply->reply_using_ai == 1 && !empty($client->open_ai_key)) {
                        $message_text = $this->AIReply($keyword, $client);
                    } else {
                        $message_text = $reply->reply_text;
                    }
                    break 2; // Break out of both loops
                } elseif ($reply->reply_type == BotReplyType::CONTAINS && stripos($conversation_text, $keyword) !== false) {
                    if ($reply->reply_using_ai == 1 && !empty($client->open_ai_key)) {
                        $message_text = $this->AIReply($keyword, $client);
                    } else {
                        $message_text = $reply->reply_text;
                    }
                    break 2; // Break out of both loops
                }

            }
        }
        return $message_text;
    }

    public function AIReply($keyword, $client)
    {
        if (isDemoMode()) {
            return __('demo_mode_notice');
        }
        $message     = null;
        $open_ai_key = $client->open_ai_key;
        $open_ai     = new OpenAi($open_ai_key);
        $use_case    = 'WhatsApp Chat Reply';
        $prompt      = 'Write a ' . $use_case . ' About ' . $keyword;
        $variants    = intval(1);
        $length      = 269 * 1;
        $result      = $open_ai->completion([
            'model'             => 'gpt-3.5-turbo-instruct',
            'prompt'            => $prompt,
            'temperature'       => 0.9,
            'max_tokens'        => (int) $length,
            'frequency_penalty' => 0,
            'presence_penalty'  => 0.6,
            'n'                 => (int) 1,
        ]);
        $result      = json_decode($result);
        if (property_exists($result, 'error')) {
            Log::info('error: ', [$result->error->message]);
        }
        if ($result->choices[0]) {
            $message = $result->choices[0]->text;
        } else {
            Log::info('error: ', ['someting went wrong']);
        }
        return $message;
    }

    public function FlowReply($message, $flow_id,$node_id,$user_response)
    {
        // dd($message->message_type);
        $data = [];
        $flow      = Flow::with('nodes', 'edges')->find($flow_id);
        if ($node_id) {
            $node = $flow->nodes->firstWhere('node_id', $node_id);
        } else {
            $node = $flow->nodes->firstWhere('type', 'starter-box');
        }
        $next_node = $this->determineNextNode($flow, $node, $user_response);
        $data['next_node_id'] = $node->id;
        $data['delay_time'] = null;
        $data['next_node_type'] = null;
        if ($next_node) {
            $data = [
                'message' => __('next_node_found'),
                'success' => true,
            ];
            $data['node'] = $this->prepareNodeResponse($next_node);
        } else {
            $data = [
                'message' => __('successfully_completed_the_flow'),
                'success' => true,
            ];
        }

    }

    private function determineNextNode($flow, $current_node, $user_response)
    {
        $data = [];
        $node_type =  $current_node->type;
        $text_reply_unique_id =  null;
        $reply_text =  null;
        $quickreply =  null;
        $button =  null;
        $next_node_id =  null;
        $next_node_type =  null;
        $delay_time =  0;
        $edge = $flow->edges->where('source', $current_node->node_id);


        if ($current_node->type == 'box-with-condition' && $user_response == 0) {
            $edge = $edge->where('sourceHandle', 'false')->first();
        } else {
            $edge = $edge->first();
        }

        if ($edge) {
            return $flow->nodes->firstWhere('node_id', $edge->target);
        }

        $data['reply_type'] = $node_type;
        $data['text_reply_unique_id'] = $text_reply_unique_id;
        $data['reply_text'] = $reply_text;
        $data['quick_replies'] = $quickreply;
        $data['buttons'] = $button;
        $data['delay_time'] = $delay_time;
        $data['next_node_id'] = $next_node_id;
        $data['next_node_type'] = $next_node_type;

        return $data; // Or handle this case as needed
    }



    public function Json()
    {
       return  $jayParsedAry = [
        "id" => "xitFB@0.0.1", 
        "nodes" => [
              [
                 "id" => 1, 
                 "data" => [
                    "title" => "July 3", 
                    "postbackId" => "6684cc795e85a", 
                    "xitFbpostbackId" => "6684cc795e85e", 
                    "buttonWebhookUrl" => "", 
                    "labelIds" => [
                       "56891" 
                    ], 
                    "labelIdTextsArray" => [
                          "Potential" 
                       ], 
                    "labelIdsRemove" => [
                             "56892" 
                          ], 
                    "labelIdTextsArrayRemove" => [
                                "Super Potential" 
                             ], 
                    "sequenceIdValue" => "", 
                    "sequenceIdText" => "Select a Sequence", 
                    "sequenceIdValueRemove" => "", 
                    "sequenceIdTextRemove" => "Select a Sequence", 
                    "conversationGroupId" => "", 
                    "conversationGroupText" => "Select Team Role", 
                    "conversationUserId" => "", 
                    "conversationUserText" => "Select Team Member", 
                    "triggerKeyword" => "Hello,Hi,Start", 
                    "triggerMatchingType" => "exact" 
                 ], 
                 "inputs" => [
                                   "referenceInputActionButton" => [
                                      "connections" => [
                                      ] 
                                   ] 
                                ], 
                 "outputs" => [
                                            "referenceOutput" => [
                                               "connections" => [
                                                  [
                                                     "node" => 3, 
                                                     "input" => "textInput", 
                                                     "data" => [
                                                     ] 
                                                  ] 
                                               ] 
                                            ], 
                                            "referenceOutputSequence" => [
                                                           "connections" => [
                                                           ] 
                                                        ] 
                                         ], 
                 "position" => [
                                                                 -939, 
                                                                 -338 
                                                              ], 
                 "name" => "Start Bot Flow" 
              ], 
              [
                                                                    "id" => 3, 
                                                                    "data" => [
                                                                       "uniqueId" => "6684cc795e86a", 
                                                                       "textMessage" => "Hi! How can I help you today?", 
                                                                       "delayReplyFor" => "1" 
                                                                    ], 
                                                                    "inputs" => [
                                                                          "textInput" => [
                                                                             "connections" => [
                                                                                [
                                                                                   "node" => 1, 
                                                                                   "output" => "referenceOutput", 
                                                                                   "data" => [
                                                                                   ] 
                                                                                ] 
                                                                             ] 
                                                                          ] 
                                                                       ], 
                                                                    "outputs" => [
                                                                                         "textOutput" => [
                                                                                            "connections" => [
                                                                                            ] 
                                                                                         ] 
                                                                                      ], 
                                                                    "position" => [
                                                                                                  -628, 
                                                                                                  -273.5 
                                                                                               ], 
                                                                    "name" => "Text" 
                                                                 ] 
           ] 
     ]; 
        

    }


 

}
