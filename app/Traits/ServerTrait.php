<?php

namespace App\Traits;

use App\Models\Client;
use App\Models\Plan;
use phpseclib3\Net\SSH2;
use App\Models\Server;
use Illuminate\Support\Str;

trait ServerTrait
{
    public function deployScript($domain=array()): array
    {
        ini_set('max_execution_time',300);
        $server               = Server::find($domain["server_id"]);

        if (!$server) {
            return ['success' => false, 'message' => 'No default server found'];
        }

        $domain_name        = $domain["domain_name"];
        $site_user          = $domain["site_user"];
        $site_password      = $domain["site_password"];
        $database_name      = $domain["database_name"];
        $database_password  = $domain["database_password"];
        $ssl_active         = $domain["ssl_active"];

        $server_ip          = $server->ip;
        $ssh                = new SSH2($server_ip);
        try {
            if ($ssh->login('root', $server->password)) {
                // Add website to CloudPanel
                $ssh->exec("clpctl site:add:php --domainName=$domain_name --phpVersion=8.2 --vhostTemplate='Generic' --siteUser='$site_user' --siteUserPassword='$site_password'");

                $ssh->exec("rm -r /home/$site_user/htdocs/$domain_name");
                // Unzip script
                $ssh->exec("unzip /home/delixfile.zip -d /home/$site_user/htdocs/$domain_name");
                // Set storage folder permission
                $ssh->exec("chmod -R 777 /home/$site_user/htdocs/$domain_name/storage");

                // Add database
                $ssh->exec("clpctl db:add --domainName=$domain_name --databaseName=$database_name --databaseUserName=$database_name --databaseUserPassword='$database_password'");
                // Import default database
                $ssh->exec("clpctl db:import --databaseName=$database_name --file=/home/delixdb.sql");

                // Update database username and database name
                $ssh->exec("sed -i 's/my_db_name/$database_name/g' /home/$site_user/htdocs/$domain_name/.env");
                $ssh->exec("sed -i 's/my_db_username/$database_name/g' /home/$site_user/htdocs/$domain_name/.env");
                $ssh->exec("sed -i 's/my_db_password/$site_password/g' /home/$site_user/htdocs/$domain_name/.env");

                // Activate SSL
                if($ssl_active):
                    $this->ActiveSsl($domain);
                    //$ssh->exec("clpctl lets-encrypt:install:certificate --domainName=$domain_name");
                endif;
            } else {
                return ['success' => false, 'message' => 'SSH login failed'];
            }

        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Exception: ' . $e->getMessage()];
        }
        return ['success' => true, 'message' => 'Operation succeeded'];
    }

    public function ActiveSsl($domain=array()): array
    {
        ini_set('max_execution_time',300);
        $server               = Server::find($domain["server_id"]);
        if (!$server) {
            return ['success' => false, 'message' => 'No server found'];
        }

        $domain_name        = $domain["domain_name"];
        $server_ip          = $server->ip;
        $ssh                = new SSH2($server_ip);
        try {
            if ($ssh->login('root', $server->password)) {
                    $ssh->exec("clpctl lets-encrypt:install:certificate --domainName=$domain_name");
            } else {
                return ['success' => false, 'message' => 'SSH login failed'];
            }
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Exception: ' . $e->getMessage()];
        }
        return ['success' => true, 'message' => 'Operation succeeded'];
    }

    public function updateClientPackageLimitation($domain=array(),$plan=array()): array
    {
        ini_set('max_execution_time',300);
        $server               = Server::find($domain["server_id"]);

        if (!$server) {
            return ['success' => false, 'message' => 'No default server found'];
        }
        $domain_name        = $domain["domain_name"];
        $site_user          = $domain["site_user"];


        $server_ip          = $server->ip;

        $ssh                = new SSH2($server_ip);
        try {
            if ($ssh->login('root', $server->password)) {
                // Update database username and database name
                $ssh->exec("sed -i 's/ACTIVE_MERCHANT=.*/ACTIVE_MERCHANT=$plan->active_merchant/g' /home/$site_user/htdocs/$domain_name/.env");
                $ssh->exec("sed -i 's/MONTHLY_PARCEL=.*/MONTHLY_PARCEL=$plan->monthly_parcel/g' /home/$site_user/htdocs/$domain_name/.env");
                $ssh->exec("sed -i 's/ACTIVE_RIDER=.*/ACTIVE_RIDER=$plan->active_rider/g' /home/$site_user/htdocs/$domain_name/.env");
                $ssh->exec("sed -i 's/ACTIVE_STAFF=.*/ACTIVE_STAFF=$plan->active_staff/g' /home/$site_user/htdocs/$domain_name/.env");
                $ssh->exec("sed -i 's/CUSTOM_DOMAIN=.*/CUSTOM_DOMAIN=$plan->custom_domain/g' /home/$site_user/htdocs/$domain_name/.env");
                $ssh->exec("sed -i 's/BRANDED_WEBSITE=.*/BRANDED_WEBSITE=$plan->branded_website/g' /home/$site_user/htdocs/$domain_name/.env");
                $ssh->exec("sed -i 's/WHITE_LEVEL=.*/WHITE_LEVEL=$plan->white_level/g' /home/$site_user/htdocs/$domain_name/.env");
                $ssh->exec("sed -i 's/MERCHANT_APP=.*/MERCHANT_APP=$plan->merchant_app/g' /home/$site_user/htdocs/$domain_name/.env");
                $ssh->exec("sed -i 's/RIDER_APP=.*/RIDER_APP=$plan->rider_app/g' /home/$site_user/htdocs/$domain_name/.env");
                $ssh->exec("sed -i 's/IS_FREE=.*/IS_FREE=$plan->is_free/g' /home/$site_user/htdocs/$domain_name/.env");
                $ssh->exec("sed -i 's/ADMIN_KEY=.*/ADMIN_KEY=$plan->is_free/g' /home/$site_user/htdocs/$domain_name/.env");
                $ssh->exec("sed -i 's/CLIENT_KEY=.*/CLIENT_KEY=$plan->is_free/g' /home/$site_user/htdocs/$domain_name/.env");
            } else {
                return ['success' => false, 'message' => 'SSH login failed'];
            }

        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Exception: ' . $e->getMessage()];
        }
        return ['success' => true, 'message' => 'Operation succeeded'];
    }
    public function updateClientPackageLimitationBySubscription($subscription): array
    {
        $client                     = Client::with('domains')->where('id', $subscription->client_id)->first();
        $domain_info['server_id']   = $client->domains->server_id;
        if($client->domains->custom_domain_active == 1):
            $domain_info['domain_name']             = $client->domains->custom_domain;
            $domain_info['site_user']               = $client->domains->custom_domain_user;
        else:
            $domain_info['domain_name']             = $client->domains->sub_domain.'.delix.cloud';
            $domain_info['site_user']               = $client->domains->sub_domain_user;
        endif;
        $plan                       = Plan::findOrfail($subscription->plan_id);
        return $this->updateClientPackageLimitation($domain_info,$plan);
    }
}



