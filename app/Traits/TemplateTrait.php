<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait TemplateTrait
{
    const GRAPH_API_BASE_URL = 'https://graph.facebook.com/v19.0/';

    // Helper method to build the header component
    private function buildHeaderComponent($requestData)
    {
        // Determine header type and build component accordingly
        switch ($requestData['header_type']) {
            case "TEXT":
                $headerText = $requestData['header_text'];
                $headerExample = !empty($requestData['header_variable']) ? ['header_text' => $requestData['header_variable']] : null;
                return [
                    'type' => 'HEADER',
                    'format' => 'TEXT',
                    'text' => $headerText,
                    'example' => $headerExample
                ];
                // Handle other header types similarly
            default:
                return null;
        }
    }

    // Helper method to build the buttons component
    private function buildButtonsComponent($requestData)
    {
        // Determine button type and build component accordingly
        switch ($requestData['button_type']) {
            case "QUICK_REPLY":
                // Build quick reply buttons
                $buttons = [];
                foreach ($requestData['button_text'] as $text) {
                    $buttons[] = [
                        "type" => "QUICK_REPLY",
                        "text" => $text
                    ];
                }
                return [
                    'type' => 'BUTTONS',
                    'buttons' => $buttons
                ];
                // Handle other button types similarly
            default:
                return null;
        }
    }

    // Helper method to send request to WhatsApp API
    private function sendRequestToWhatsAppAPI($messageTemplate)
    {
        $accessToken = getClientWhatsAppAccessToken(Auth::user()->client);
        $whatsappBusinessAccountId = getClientWhatsAppBusinessAcID(Auth::user()->client);
        $apiUrl = self::GRAPH_API_BASE_URL . "{$whatsappBusinessAccountId}/message_templates";

        $curl = curl_init($apiUrl);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($messageTemplate));
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json',
        ]);
        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response);
    }
}
