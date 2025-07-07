<?php

namespace App\Http\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

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

    public static function sendMail($message,$email)
    {

        $response = Mail::raw($message, function ($message) use ($email) {
            $message->to($email)
                ->subject('OTP Code');
        });

        return $response ? 200 : 500;

    }
}
