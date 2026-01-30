<?php

namespace App\Http\Helpers;

use App\Models\Customer;
use App\Models\Guest;
use App\Models\GuestNotification;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FirebaseHelper
{
    private static $clientEmail = "firebase-adminsdk-fbsvc@mhmv-47019.iam.gserviceaccount.com";
    private static $privateKey = "-----BEGIN PRIVATE KEY-----\nMIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQC5sN9uhqJQDp0/\n9Y9lPeU8bKCOozL9iZkrM2KHgUWdGn2VqOJ+RPaSzNyrX7J8XIy1BOh71FNM0u5e\n+kuUpQvU0Kn8dy3hvZwObmMnzX1JNua563eMPipvh4VBSGm+8O5nKRTBFzhxEkef\n7l8eAxAMxRLXwvinfDG1k7K+9X83d91d8htwsqcumR8ne2l6X+Qd3qp1O6tRGxqB\ngiVeE6Nrhl06s080qu+onF4BkzaPOYfHe2dl2pA4/5M17ZoCHCYWzQHVzao6opsX\nf4vo2dvJ1l6Q75yUc7MoaHv8OF2PYwM3mA7bqwyL8NX+/SwG/eLbDPrfXFuvOXfT\nMFF9ymyhAgMBAAECggEABEMCE8MxBzmYpBLGll4h7F25XyCxf0ZozqgkoSPHRVxp\n6LUKMrfyOUsMnv3IpshIffCVhd8JBOS77g/cS3Zww7MAzb+t/MX5Zo7jxXJ2x0cE\na1yzPxOfrXTcqvOGoshcjl9P38EgzV6MKIhj5DhRhluBC9TomE52RRq1UeLoRy11\n8vb4QN0yGPpY5PVPMt5LUsWDN+xxhgw/t/YkAy/tVJ374O95FRZ51PVL3pW76Yo+\nvP2HQJT6iS6k3XN3+AeSIVo85haxh5xjh62Rt/BqVkxbNuVJ1+Ia7uuLY627ExJv\nHKxSYg+K7TBRg3V8b+dY5ZHW6vuAH15+yviLb4yBmQKBgQDgS+MkoGfYgOB6L/hA\naElW0gQCj+UKF8s3H+HxO2joPKLbjx7HGWQfO4xrF0c8wdqbCcTBnU756ULRfxHx\nWSJFjh3g/h9pVETEFa4nklelPVlpWZDKlLTqMJVk8EryaHnQc9KW/Ko3OnxJRbaS\nBko9G0vgvacERaDoVyenNXWSOQKBgQDT8A3HNSTA0gaIMZ8sh4wwvVc4vujnODOy\nPkULotupBzixECEHEHr48cufSGYk8YhSHjwKwZd1sOlRxXA2myQEPwKyEp4V9UBk\njvWj/aRlszJYSvbJzN1Ye9ayVgy8DRplnVbJeuWEwOo6mSafNmoyuu1gigLvJT27\nYVcfEMANqQKBgFCArDvfHqaESw1P3kgvpfL1Wd8Zilk/BP76AHw2mIaSDNnE2oUX\nReo300Q0jKrv4Og4b1gWf9wOrp7Gfsgi97wDMBIq41dImY3PQjyNt8tk44x+SwuK\nqAxN97DM1fl/Kgl5KKJseSNtaGJcFRUAEPExtOAq8aEE5KW/ckn/U/1xAoGAeeQK\nSJPr+2nPj+zaGwYqPq3myCAkWzcbpFvj4flcVC5vEIayOBnmX97QuV8uP/kZ6gCx\nj44fyfRhfEINc5pucHK83iWO/hn7WtpNtG9gaY1SWy1iGlXUx9sRc6fB0zXGBMMk\n8uPXtS4uiF0ktVTaZyrS8z7syRWmF2q4bIl5q2ECgYAkIQPMyIcqZJOBuo4YhUWU\nIBGVzaVw10a01HXg253ApdQUsOgGvDcB+maSxKSfMma8V2tpTL7ibJ19Nb7hzWqU\nRPNMdpSqL68oYkyi4meoksxP8F9EXRW0tZ8DOGojvc5OpXtGB7xNQiYnL596Gx1b\nIjx/Dv2sYSd2VLD2GDkgFA==\n-----END PRIVATE KEY-----\n";

    private static $notifications = [
        'sendOffer' => ['title' => 'Xəbərdarlıq', 'content' => 'Hörmətli X0, Sizin X1 nömrəli sifarişiniz üzrə təklif hazırdır.'],
        'canceledOffer' => ['title' => 'Sifariş ləğv edildi', 'content' => 'Hörmətli X0, Sizin X1 nömrəli sifarişiniz X2 səbəbdən ləğv edildi.'],
        'completedOffer' => ['title' => 'Sifarişiniz tamamlandı', 'content' => 'Hörmətli X0, Sizin X1 nömrəli sifarişiniz tamamlandı.'],
        'newMessage' => ['title' => 'Yeni mesaj', 'content' => 'Hörmətli X0, sizə admindən yeni mesaj var.'],
    ];


    public static function sendFirebaseRequest($data, $accessToken = null)
    {
        $token = $accessToken ?? self::getAccessToken();

        try {
            $response = Http::withToken($token)
                ->timeout(10)
                ->post("https://fcm.googleapis.com/v1/projects/mhmv-47019/messages:send", $data);

            return $response->json();
        } catch (\Exception $e) {
            Log::error("Firebase Request Hatası: " . $e->getMessage());
            return null;
        }
    }

    public static function sendAll($title, $desc, $subdirectionIds = [])
    {
        if (empty($subdirectionIds)) return;

        GuestNotification::create([
            'title' => $title,
            'description' => $desc,
            'all' => true
        ]);

        $accessToken = self::getAccessToken();

        Guest::whereIn('sub_direction_id', $subdirectionIds)
            ->with("parameters")
            ->chunk(500, function ($guests) use ($title, $desc, $accessToken) {
                foreach ($guests as $guest) {
                    $targetToken = $guest->parameters->token ?? null;

                    if ($targetToken) {
                        dispatch(function () use ($targetToken, $title, $desc, $accessToken) {
                            self::sendFirebaseRequest([
                                "message" => [
                                    "token" => $targetToken,
                                    "notification" => [
                                        "title" => $title,
                                        "body" => $desc
                                    ]
                                ]
                            ], $accessToken);
                        })->onQueue('notifications');
                    }
                }
            });
    }


    public static function send($type, $details, $userId)
    {
        $user = Customer::with("parameters")->find($userId);
        if (!$user || empty($user->parameters->token)) return ['status' => 'error'];

        $message = self::generate($type, $details);
        Notification::create(array_merge($message, ['customer_id' => $userId]));

        $token = $user->parameters->token;
        $accessToken = self::getAccessToken();

        dispatch(function () use ($token, $message, $accessToken) {
            self::sendFirebaseRequest([
                "message" => [
                    "token" => $token,
                    "notification" => [
                        "title" => $message['title'],
                        "body" => $message['content']
                    ]
                ]
            ], $accessToken);
        })->onQueue('notifications');

        return ['status' => 'success'];
    }

    public static function sendGuest($title, $desc, $userId)
    {
        $user = Guest::with("parameters")->find($userId);
        if (!$user || empty($user->parameters->token)) return ['status' => 'error'];

        Notification::create([
            'title' => $title,
            'description' => $desc,
            'customer_id' => $userId
        ]);

        $token = $user->parameters->token;
        $accessToken = self::getAccessToken();

        dispatch(function () use ($token, $title, $desc, $accessToken) {
            self::sendFirebaseRequest([
                "message" => [
                    "token" => $token,
                    "notification" => [
                        "title" => $title,
                        "body" => $desc
                    ]
                ]
            ], $accessToken);
        })->onQueue('notifications');

        return ['status' => 'success'];
    }

    public static function sendUser($title, $desc, $userId)
    {
        return self::sendGuest($title, $desc, $userId);
    }

    public static function generate($type, $details)
    {
        $res = self::$notifications[$type];
        foreach ($details as $n => $detail) {
            $res['content'] = str_replace("X" . $n, $detail, $res['content']);
        }
        return $res;
    }


    public static function getAccessToken()
    {
        return Cache::remember('firebase_access_token', 3500, function () {
            $now = time();
            $header = base64_encode(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
            $payload = base64_encode(json_encode([
                'iss' => self::$clientEmail,
                'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
                'aud' => 'https://oauth2.googleapis.com/token',
                'exp' => $now + 3600,
                'iat' => $now
            ]));

            $signature = '';
            openssl_sign("$header.$payload", $signature, self::$privateKey, 'SHA256');
            $jwt = "$header.$payload." . base64_encode($signature);

            $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $jwt,
            ]);

            return $response->json('access_token');
        });
    }

    public static function subdirectionIds()
    {
        $user = Auth::guard('teacher')->user();
        if (!$user) return [];

        return $user->subDirection
            ->map(fn($tsd) => $tsd->subDirection?->id)
            ->filter()
            ->values()
            ->all();
    }
}
