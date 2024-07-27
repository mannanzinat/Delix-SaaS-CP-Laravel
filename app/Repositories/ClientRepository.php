<?php

namespace App\Repositories;

use App\Models\BotGroup;
use App\Models\BotReply;
use App\Models\Campaign;
use App\Models\Client;
use App\Models\ClientSetting;
use App\Models\ClientStaff;
use App\Models\Contact;
use App\Models\Domain;
use App\Models\ContactsList;
use App\Models\Conversation;
use App\Models\GroupSubscriber;
use App\Models\Message;
use App\Models\Segment;
use App\Models\StripeSession;
use App\Models\Subscription;
use App\Models\SubscriptionTransactionLog;
use App\Models\Template;
use App\Models\Ticket;
use App\Models\Server;
use App\Models\User;
use App\Traits\DnsTrait;
use App\Traits\ImageTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use phpseclib3\Net\SSH2;
use App\Traits\ServerTrait;

class ClientRepository
{
    use ImageTrait;
    use ServerTrait;
    use DnsTrait;

    public function all($data, $relation = []): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        if (! arrayCheck('paginate', $data)) {
            $data['paginate'] = setting('paginate');
        }

        return Client::with($relation)->latest()->paginate($data['paginate']);
    }

    public function activeClient()
    {
        return Client::with('user')->latest()->where('status', 1)->get();
    }

    public function bestClient()
    {
        return Client::withCount('subscriptions')->withSum('subscriptions', 'price')->where('status', 1)->orderByDesc('subscriptions_count')->limit(5)->get();
    }

    public function clientStatus($status = null)
    {
        return Client::when($status || $status == '0', function ($query) use ($status) {
            return $query->where('status', $status);
        })->count();
    }

    public function find($id)
    {
        return Client::find($id);
    }

    public function store($request)
    {
        try {

            $response = [];
            if (arrayCheck('images', $request)) {
                $requestImage                       = $request['images'];
                $response                           = $this->saveImage($requestImage, '_user_');
            }
            $response2                              = [];
            if (arrayCheck('logo', $request)) {
                $requestImage                       = $request['logo'];
                $response2                          = $this->saveImage($requestImage, '_client_');
            }
            $request['slug']                        = getSlug('clients', $request['company_name']);
            $request['webhook_verify_token']        = Str::random(40);
            $request['api_key']                     = Str::random(40);
            $request['logo']                        = $response2['images'] ?? null;
            $request['country_id']                  = $request['country_id'] ?? null;
            $client                                 = Client::create($request);

            // User
            $role                                   = DB::table('roles')->where('slug', 'Client-staff')->select('id', 'permissions')->first();
            $permissions                            = json_decode($role->permissions, true);
            $request['permissions']                 = $permissions;

            $request['first_name']                  = $request['first_name'];
            $request['last_name']                   = $request['last_name'];
            $request['role_id']                     = $role->id;
            $request['email']                       = $request['email'];
            $request['user_type']                   = 'client-staff';
            $request['phone']                       = $request['phone_number'];
            $request['client_id']                   = $client->id;
            $request['is_primary']                  = 1;
            $request['email_verified_at']           = now();
            if (arrayCheck('password', $request)) {
                $request['password']                = bcrypt($request['password']);
            }
            $request['images']                      = $response['images'] ?? null;
            $user                                   = User::create($request);

            // ClientStaff
            $request['user_id']                     = $user->id;
            $request['client_id']                   = $client->id;
            $request['slug']                        = getSlug('clients', $client->company_name);

            $clientStaff                            = ClientStaff::create($request);


            if($request['create_domain'] =='1'):
                $result = $this->dnsAdd($request['sub_domain']);
                if (!$result['success']):
                    return ['success' => false, 'message' => $result['message']];
                else:
                    $domain                             = new Domain;
                    $domain->script_deployed            = 0;
                    $domain->ssl_active                 = 0;
                    $server                             = Server::where('default', 1)->first();
                    $domain->client_id                  = $client->id;
                    $domain->server_id                  = $server->id;
                    $domain->sub_domain                 = $request['sub_domain'];
                    $uid                                = strtolower(Str::random(4));
                    $domain->sub_domain_user            = strtolower("delix-".$request['sub_domain']. $uid);
                    $domain->sub_domain_password        = Str::random(20);
                    $domain->sub_domain_db_name         = strtolower("db" . $uid . "db");
                    $domain->sub_domain_db_user         = strtolower("db" . $uid . "db");
                    $domain->sub_domain_db_password     = Str::random(20);
                    $uid                                = strtolower(Str::random(4));
                    $domain->custom_domain              = "";
                    $domain->custom_domain_user         = strtolower("delix-".$request['sub_domain']. $uid);
                    $domain->custom_domain_password     = Str::random(20);
                    $domain->custom_domain_db_name      = strtolower("db" . $uid . "db");
                    $domain->custom_domain_db_user      = strtolower("db" . $uid . "db");
                    $domain->custom_domain_db_password  = Str::random(20);

                    if($request['script_deployed'] =='1'):
                        if($request['ssl_active'] =='1'):
                            $domain->ssl_active                 = 1;
                        endif;
                        $domain_info['server_id']               = $server->id;
                        $domain_info['domain_name']             = $domain->sub_domain.'.delix.cloud';
                        $domain_info['site_user']               = $domain->sub_domain_user;
                        $domain_info['site_password']           = $domain->sub_domain_password;
                        $domain_info['database_name']           = $domain->sub_domain_db_name;
                        $domain_info['database_user']           = $domain->sub_domain_user;
                        $domain_info['database_password']       = $domain->sub_domain_password;
                        $domain_info['admin_key']               = strtolower(Str::random(24));
                        $domain_info['client_key']              = strtolower(Str::random(24));
                        $domain_info['database_password']       = $domain->sub_domain_password;
                        $domain_info['ssl_active']              = ($domain->ssl_active == 1) ? true:false;
                        $result                                 = $this->deployScript($domain_info);
                        if ($result['success']):
                            $domain->script_deployed            = 1;
                        endif;
                    endif;
                    $domain->save();

                    if (!$result['success']):
                        return ['success' => false, 'message' => $result['message']];
                    endif;
                endif;
            endif;
            return ['success' => true, 'message' => 'Client staff created successfully', 'data' => $clientStaff];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Exception: ' . $e->getMessage()];
        }
    }



    public function update($request, $id)
    {

        $response2            = [];
        if (arrayCheck('logo', $request)) {
            $requestImage = $request['logo'];
            $response2    = $this->saveImage($requestImage, '_client_');
        }

        $client               = Client::findOrFail($id);
        $client->company_name = $request['company_name'];
        $client->slug         = getSlug('clients', $request['company_name']);
        $client->timezone     = $request['time_zone'] ?? null;
        $client->country_id   = $request['country_id'] ?? null;
        $client->logo         = $response2['images']  ?? $client->logo;
        $client->save();

        $clientStaff          = ClientStaff::where('client_id', $client->id)->first();
        $clientStaff->slug    = getSlug('clients', $client->company_name);

        return $clientStaff->save();
    }

    public function destroy($id): int
    {
        return Client::destroy($id);
    }

    public function statusChange($request)
    {
        $id = $request['id'];

        return Client::find($id)->update($request);
    }

    public function delete($id)
    {
        $subscription_transaction_logs = SubscriptionTransactionLog::where('client_id', $id)->delete();
        $tickets                       = Ticket::where('client', $id)->delete();
        $stripe_sessions               = StripeSession::where('client_id', $id)->delete();
        $subscriptions                 = Subscription::where('client_id', $id)->delete();
        $bot_replies                   = BotReply::where('client_id', $id)->delete();
        $conversations                 = Conversation::where('client_id', $id)->delete();
        $templates                     = Template::where('client_id', $id)->delete();
        $messages                      = Message::where('client_id', $id)->delete();
        $campaigns                     = Campaign::where('client_id', $id)->delete();
        $group_subscribers             = GroupSubscriber::where('client_id', $id)->delete();
        $bot_groups                    = BotGroup::where('client_id', $id)->delete();
        $segments                      = Segment::where('client_id', $id)->delete();
        $contacts_lists                = ContactsList::where('client_id', $id)->delete();
        $contacts                      = Contact::where('client_id', $id)->delete();
        $client_staff                  = ClientStaff::where('client_id', $id)->delete();
        $client_settings               = ClientSetting::where('client_id', $id)->delete();
        $user                          = User::where('client_id', $id)->delete();
        $client                        = Client::destroy($id);
    }

    public function server()
    {
        
    }
}
