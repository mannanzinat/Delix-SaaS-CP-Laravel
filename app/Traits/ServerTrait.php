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

    public function deployScript($sub_domain,$ssl_active=false)
    {
        ini_set('max_execution_time',300);
        $server               = Server::where('default', 1)->first();

        if (!$server) {
            return ['success' => false, 'message' => 'No default server found'];
        }

        $domain             = $sub_domain . ".delix.cloud";
        $uid                = strtolower(Str::random(4));
        $database_name      = strtolower("db" . $uid . "db");
        $site_user          = strtolower("delix-$sub_domain" . $uid);
        $site_password      = Str::random(20);
        $server_ip          = $server->ip;

        $ssh                = new SSH2($server_ip);
        try {
            if ($ssh->login('root', $server->password)) {
                // Add website to CloudPanel
                $ssh->exec("clpctl site:add:php --domainName=$domain --phpVersion=8.2 --vhostTemplate='Generic' --siteUser='$site_user' --siteUserPassword='$site_password'");

                $ssh->exec("rm -r /home/$site_user/htdocs/$domain");
                // Unzip script
                $ssh->exec("unzip /home/delixfile.zip -d /home/$site_user/htdocs/$domain");
                // Set storage folder permission
                $ssh->exec("chmod -R 777 /home/$site_user/htdocs/$domain/storage");

                // Add database
                $ssh->exec("clpctl db:add --domainName=$domain --databaseName=$database_name --databaseUserName=$database_name --databaseUserPassword='$site_password'");
                // Import default database
                $ssh->exec("clpctl db:import --databaseName=$database_name --file=/home/delixdb.sql");

                // Update database username and database name
                $ssh->exec("sed -i 's/my_db_name/$database_name/g' /home/$site_user/htdocs/$domain/.env");
                $ssh->exec("sed -i 's/my_db_username/$database_name/g' /home/$site_user/htdocs/$domain/.env");
                $ssh->exec("sed -i 's/my_db_password/$site_password/g' /home/$site_user/htdocs/$domain/.env");

                // Activate SSL
                if($ssl_active):
                    $ssh->exec("clpctl lets-encrypt:install:certificate --domainName=$domain");
                endif;
            } else {
                return ['success' => false, 'message' => 'SSH login failed'];
            }

        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Exception: ' . $e->getMessage()];
        }
        return ['success' => true, 'message' => 'Operation succeeded'];
    }

    public function updateClientPackageLimitation($client,$subscription_package)
    {
        //dd($client);
        $domain_name        = $client->domains->sub_domain.'.delix.cloud';
        $site_user          = $client->domains->site_user;
        $site_user          = "delix-sfsdfewrefgdergtetertk7or";
        if($client->domains->custom_domain_active == 1):
            $domain_name = $client->domains->custom_domain;
        endif;
        ini_set('max_execution_time',300);
        $server               = Server::find($client->domains->server_id);

        if (!$server) {
            return ['success' => false, 'message' => 'No default server found'];
        }
        $server_ip          = $server->ip;

        $ssh                = new SSH2($server_ip);
        try {
            if ($ssh->login('root', $server->password)) {
                // Update database username and database name
                dd($ssh->exec("sed -i 's/ACTIVE_MERCHANT=.*/ACTIVE_MERCHANT=9999/g' /home/$site_user/htdocs/$domain_name/.env"));
            } else {
                return ['success' => false, 'message' => 'SSH login failed'];
            }

        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Exception: ' . $e->getMessage()];
        }
        return ['success' => true, 'message' => 'Operation succeeded'];
    }
}



