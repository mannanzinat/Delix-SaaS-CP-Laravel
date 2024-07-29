<?php

namespace App\Traits;

use phpseclib3\Net\SSH2;
use App\Models\Server;
use Illuminate\Support\Str;

trait DnsTrait
{
    public function dnsAdd($sub_domain): array
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
    public function dnsUpdate($sub_domain): array
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
    public function dnsRemove($sub_domain): array
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
}



