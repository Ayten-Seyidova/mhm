<?php

namespace App\Http\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class SmsHelper{

    private static $userName = 'mhmsender';
    private static $apiKey = 'superSecret727';
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

    // public static function sendMail($message,$email)
    // {

    //     $response = Mail::raw($message, function ($message) use ($email) {
    //         $message->to($email)
    //             ->subject('OTP Code');
    //     });

    //     return $response ? 200 : 500;

    // }

    //  public static function sendMail($message,$email)
    // {
    //     $endpoint = "https://send.mhmapp.az/send_email.php";
    //     $client = new \GuzzleHttp\Client();

    //     $response = $client->request('POST', $endpoint, ['query' => [
    //         'user' => self::$userName,
    //         'password' => self::$apiKey,
    //         'email' => $email,
    //         'message' => $message,
    //         // 'text' => $message,
    //     ]]);

    //     $statusCode = $response->getStatusCode();
    //    // $content = $response->getBody();

    //     return $statusCode;
    // }

    public static function sendMail($message,$email)
    {
        $endpoint = "https://send.mhmapp.az/send_email.php";
        
          $response = Http::withBasicAuth('mhmsender', 'superSecret727')
            ->asForm()
            ->post('https://send.mhmapp.az/send_email.php', [
                'email' => $email,
                'message' => $message,
            ]);

        dd($response);

        return $response->status();
    }
}
