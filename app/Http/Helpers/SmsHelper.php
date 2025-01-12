<?php

namespace App\Http\Helpers;

class SmsHelper{

    private static $userName = 'advanced_english_api';
    private static $apiKey = 'aaRSj30W';
    private static $from = 'TS English';

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
