<?php

namespace App\Http\Helpers;

class SmsHelper{

    private static $userName = 'mhm_api';
    private static $apiKey = 'NszsCilq';
    private static $from = 'MHM TM';

    public static function send($message,$phoneNumber)
    {
        $endpoint = "http://api.msm.az/sendsms";
        $client = new \GuzzleHttp\Client();

        $response = $client->request('GET', $endpoint, ['query' => [
            'user' => self::$userName,
            'password' => self::$apiKey,
            'gsm' => $phoneNumber,
            'from' => self::$from,
            'text' => $message,
        ]]);

        $statusCode = $response->getStatusCode();
       // $content = $response->getBody();

        return $statusCode;
    }

}
