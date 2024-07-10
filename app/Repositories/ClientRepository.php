<?php

namespace App\Repositories;

use App\Models\BotGroup;
use App\Models\BotReply;
use App\Models\Campaign;
use App\Models\Client;
use App\Models\ClientSetting;
use App\Models\ClientStaff;
use App\Models\Contact;
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
use App\Traits\ImageTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use phpseclib3\Net\SSH2;

class ClientRepository
{
    use ImageTrait;

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

        $response                        = [];
        if (arrayCheck('images', $request)) {
            $requestImage = $request['images'];
            $response     = $this->saveImage($requestImage, '_user_');
        }
        $response2                       = [];
        if (arrayCheck('logo', $request)) {
            $requestImage = $request['logo'];
            $response2    = $this->saveImage($requestImage, '_client_');
        }
        $request['slug']                 = getSlug('clients', $request['company_name']);
        $request['domain']               = $request['domain'];
        $request['webhook_verify_token'] = Str::random(40);
        $request['api_key']              = Str::random(40);
        $request['logo']                 = $response2['images'] ?? null;
        $request['country_id']           = $request['country_id'] ?? null;
        $client                          = Client::create($request);

        //user
        $role                            = DB::table('roles')->where('slug', 'Client-staff')->select('id', 'permissions')->first();
        $permissions                     = json_decode($role->permissions, true);
        $request['permissions']          = $permissions;

        $request['first_name']           = $request['first_name'];
        $request['last_name']            = $request['last_name'];
        $request['role_id']              = $role->id;
        $request['email']                = $request['email'];
        $request['user_type']            = 'client-staff';
        $request['phone']                = $request['phone_number'];
        $request['client_id']            = $client->id;
        $request['is_primary']           = 1;
        $request['email_verified_at']    = now();
        if (arrayCheck('password', $request)) {
            $request['password'] = bcrypt($request['password']);
        }
        $request['images']               = $response['images']  ?? null;
        $user                            = User::create($request);
        //ClientStaff
        $request['user_id']              = $user->id;
        $request['client_id']            = $client->id;
        $request['slug']                 = getSlug('clients', $client->company_name);

        $server             =   Server::where('default', 1)->first();
        $server_ip          =   $server->ip;
        $server_username    =   $server->user_name;
        $server_password    =   $server->password;

        $uid                =   strtolower(Str::random(10));
        $domain_prefix      =   strtolower($request['domain']);
        $domain             =   $domain_prefix.".delix.cloud";
        $database_name      =   strtolower("db".$uid."db");
        $site_user          =   strtolower("delix-". $domain_prefix);
        $site_password      =   Str::random(20);

        try {
            // update dns
            $zoneID             = "1ea19630bbad09fbd8c69f5d7a703168";
            $apiKey             = "21e4220da546e136cc107911a3a8f69eb0c66";
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL                 => "https://api.cloudflare.com/client/v4/zones/$zoneID/dns_records",
                CURLOPT_RETURNTRANSFER      => true,
                CURLOPT_ENCODING            => "",
                CURLOPT_MAXREDIRS           => 10,
                CURLOPT_TIMEOUT             => 30,
                CURLOPT_HTTP_VERSION        => CURL_HTTP_VERSION_1_1,
                CURLOPT_SSL_VERIFYPEER      => false,
                CURLOPT_CUSTOMREQUEST       => "POST",
                CURLOPT_POSTFIELDS          => json_encode([
                    "content"       => $server_ip,
                    "name"          => $domain,
                    "proxied"       => false,
                    "type"          => "A",
                    "comment"       => "Domain verification record",
                    "id"            => "8d6ff21ce5ab60dec3c66238f82c1714",
                    "ttl"           => 3600,

                ]),
                CURLOPT_HTTPHEADER          => [
                    "Content-Type: application/json",
                    "X-Auth-Email: mannanzinat@gmail.com",
                    "X-Auth-Key: $apiKey"
                ],
            ]);

            $response       = curl_exec($curl);
            $err            = curl_error($curl);

            curl_close($curl);

            if ($err) {
                echo "cURL Error #:" . $err;
            }

            $ssh = new SSH2($server_ip);
            if ($ssh->login($server_username, $server_password)):
                // add website to CloudPanel
                $ssh->exec("clpctl site:add:php --domainName=$domain --phpVersion=8.2 --vhostTemplate='Generic' --siteUser='$site_user' --siteUserPassword='$site_password'");

                $ssh->exec("rm -r /home/$site_user/htdocs/$domain");
                // unzip script
                $ssh->exec("unzip /home/delixfile.zip -d /home/$site_user/htdocs/$domain");
                // set storage folder permission
                $ssh->exec("chmod -R 777 /home/$site_user/htdocs/$domain/storage");

                // add database
                $ssh->exec("clpctl db:add --domainName=$domain --databaseName=$database_name --databaseUserName=$database_name --databaseUserPassword='$site_password'");
                //import default database
                $ssh->exec("clpctl db:import --databaseName=$database_name --file=/home/delixdb.sql");

                // update database username and database name
                $ssh->exec("sed -i 's/my_db_name/$database_name/g' /home/$site_user/htdocs/$domain/.env");
                $ssh->exec("sed -i 's/my_db_username/$database_name/g' /home/$site_user/htdocs/$domain/.env");
                $ssh->exec("sed -i 's/my_db_password/$site_password/g' /home/$site_user/htdocs/$domain/.env");

                // active SSL
                //$ssh->exec("clpctl lets-encrypt:install:certificate --domainName=$domain");

                //$ssh->exec("rm -r /home/$domain_prefix/htdocs/$domain/public");
            else:

                echo 'SSH login failed.';
            endif;

            return ClientStaff::create($request);


        }catch (Exception $e){
            dd($e);
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
