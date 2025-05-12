<?php

namespace App\Http\Helpers;

use GuzzleHttp\Exception\ClientException;

class WhatsappHelper{

    private static $templateName = 'otp';
    private static $broadcastName = 'otp_120520251541';
    private static $apiToken = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiJkZjcyMGU3Yi0yZmMyLTQ5ZDMtYTBjMy0wZTg5NTg1MDRjNmMiLCJ1bmlxdWVfbmFtZSI6ImFtaW4uc2FtZWR6YWRlaEBnbWFpbC5jb20iLCJuYW1laWQiOiJhbWluLnNhbWVkemFkZWhAZ21haWwuY29tIiwiZW1haWwiOiJhbWluLnNhbWVkemFkZWhAZ21haWwuY29tIiwiYXV0aF90aW1lIjoiMDUvMDgvMjAyNSAxODowNToxMCIsInRlbmFudF9pZCI6IjQzNzQ1NiIsImRiX25hbWUiOiJtdC1wcm9kLVRlbmFudHMiLCJodHRwOi8vc2NoZW1hcy5taWNyb3NvZnQuY29tL3dzLzIwMDgvMDYvaWRlbnRpdHkvY2xhaW1zL3JvbGUiOiJBRE1JTklTVFJBVE9SIiwiZXhwIjoyNTM0MDIzMDA4MDAsImlzcyI6IkNsYXJlX0FJIiwiYXVkIjoiQ2xhcmVfQUkifQ.VE8HoKo3c0XhLizNd2M_y9tWC-3m5OHIvStMitgHazU';

    public static function send($message,$phoneNumber)
    {
        $endpoint = "https://live-mt-server.wati.io/437456/api/v1/sendTemplateMessage?whatsappNumber=" . $phoneNumber;
        $client = new \GuzzleHttp\Client();

        $requestBody = [
            'headers' => [
                'Authorization' => 'Bearer ' . self::$apiToken,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'template_name' => self::$templateName,
                'broadcast_name' => self::$broadcastName,
                'parameters' => [['name' => '1', 'value' => $message]],
            ]
        ];
    try{
        $response = $client->request('POST', $endpoint,
            $requestBody);
        
        $statusCode = $response->getStatusCode();
dd([$response->getResponse()->getBody()->getContents(),$response,$endpoint,$requestBody]);

    }catch (ClientException $e) {
        $errorResponse = $e->getResponse()->getBody()->getContents();
dd(['err',$response,$errorResponse,$endpoint,$requestBody]);

        return [
            'error' => true,
            'message' => $e->getMessage(),
            'response' => json_decode($errorResponse, true),
            'requestBody' => $requestBody,
        ];
    }

        return $statusCode;
    }

}
