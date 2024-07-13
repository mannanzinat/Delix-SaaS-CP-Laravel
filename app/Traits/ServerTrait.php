<?php

namespace App\Traits;

use phpseclib3\Net\SSH2;
use App\Models\Server;
use Illuminate\Support\Str;

trait ServerTrait
{
    public function dnsUpdate($sub_domain)
    {
        $server             = Server::where('default', 1)->first();

        if (!$server):
            return ['success' => false, 'message' => 'No default server found'];
        endif;

        $domain             = $sub_domain . ".delix.cloud";
        $uid                = strtolower(Str::random(4));
        $server_ip          = $server->ip;
        $zoneID             = "1ea19630bbad09fbd8c69f5d7a703168";
        $apiKey             = "21e4220da546e136cc107911a3a8f69eb0c66";

        // Update DNS
        try {
            $curl = curl_init();
            $cf_data = [
                "content"       => $server_ip,
                "name"          => $domain,
                "proxied"       => false,
                "type"          => "A",
                "comment"       => "Domain verification record",
                "ttl"           => 3600,
            ];
            curl_setopt_array($curl, [
                CURLOPT_URL             => "https://api.cloudflare.com/client/v4/zones/$zoneID/dns_records",
                CURLOPT_RETURNTRANSFER  => true,
                CURLOPT_ENCODING        => "",
                CURLOPT_MAXREDIRS       => 10,
                CURLOPT_TIMEOUT         => 30,
                CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
                CURLOPT_SSL_VERIFYPEER  => false,
                CURLOPT_CUSTOMREQUEST   => "POST",
                CURLOPT_POSTFIELDS      => json_encode($cf_data),
                CURLOPT_HTTPHEADER      => [
                    "Content-Type: application/json",
                    "X-Auth-Email: mannanzinat@gmail.com",
                    "X-Auth-Key: $apiKey"
                ],
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            if ($err) {
                return ['success' => false, 'message' => 'cURL Error: ' . $err];
            }

            // Check Cloudflare response
            $cf_response = json_decode($response, true);

            if (isset($cf_response['success']) && !$cf_response['success']) {
                return ['success' => false, 'message' => 'Cloudflare API Error'];
            }

        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Exception: ' . $e->getMessage()];
        }
        return ['success' => true, 'message' => 'Operation succeeded'];
    }

    public function deployScript($domain=array())
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

    public function updateClientPackageLimitation($domain=array(),$plan)
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
            } else {
                return ['success' => false, 'message' => 'SSH login failed'];
            }

        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Exception: ' . $e->getMessage()];
        }
        return ['success' => true, 'message' => 'Operation succeeded'];
    }
}



