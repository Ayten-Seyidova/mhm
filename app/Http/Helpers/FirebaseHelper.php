<?php

namespace App\Http\Helpers;
use App\Models\Customer;
use App\Models\NotificationParameters;
use App\Models\Notification;


class FirebaseHelper
{
    // private static $key = self::getAccessToken();

    private static $clientEmail = "firebase-adminsdk-fbsvc@mhmv-47019.iam.gserviceaccount.com";
    private static $privateKey = "-----BEGIN PRIVATE KEY-----\nMIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQC5sN9uhqJQDp0/\n9Y9lPeU8bKCOozL9iZkrM2KHgUWdGn2VqOJ+RPaSzNyrX7J8XIy1BOh71FNM0u5e\n+kuUpQvU0Kn8dy3hvZwObmMnzX1JNua563eMPipvh4VBSGm+8O5nKRTBFzhxEkef\n7l8eAxAMxRLXwvinfDG1k7K+9X83d91d8htwsqcumR8ne2l6X+Qd3qp1O6tRGxqB\ngiVeE6Nrhl06s080qu+onF4BkzaPOYfHe2dl2pA4/5M17ZoCHCYWzQHVzao6opsX\nf4vo2dvJ1l6Q75yUc7MoaHv8OF2PYwM3mA7bqwyL8NX+/SwG/eLbDPrfXFuvOXfT\nMFF9ymyhAgMBAAECggEABEMCE8MxBzmYpBLGll4h7F25XyCxf0ZozqgkoSPHRVxp\n6LUKMrfyOUsMnv3IpshIffCVhd8JBOS77g/cS3Zww7MAzb+t/MX5Zo7jxXJ2x0cE\na1yzPxOfrXTcqvOGoshcjl9P38EgzV6MKIhj5DhRhluBC9TomE52RRq1UeLoRy11\n8vb4QN0yGPpY5PVPMt5LUsWDN+xxhgw/t/YkAy/tVJ374O95FRZ51PVL3pW76Yo+\nvP2HQJT6iS6k3XN3+AeSIVo85haxh5xjh62Rt/BqVkxbNuVJ1+Ia7uuLY627ExJv\nHKxSYg+K7TBRg3V8b+dY5ZHW6vuAH15+yviLb4yBmQKBgQDgS+MkoGfYgOB6L/hA\naElW0gQCj+UKF8s3H+HxO2joPKLbjx7HGWQfO4xrF0c8wdqbCcTBnU756ULRfxHx\nWSJFjh3g/h9pVETEFa4nklelPVlpWZDKlLTqMJVk8EryaHnQc9KW/Ko3OnxJRbaS\nBko9G0vgvacERaDoVyenNXWSOQKBgQDT8A3HNSTA0gaIMZ8sh4wwvVc4vujnODOy\nPkULotupBzixECEHEHr48cufSGYk8YhSHjwKwZd1sOlRxXA2myQEPwKyEp4V9UBk\njvWj/aRlszJYSvbJzN1Ye9ayVgy8DRplnVbJeuWEwOo6mSafNmoyuu1gigLvJT27\nYVcfEMANqQKBgFCArDvfHqaESw1P3kgvpfL1Wd8Zilk/BP76AHw2mIaSDNnE2oUX\nReo300Q0jKrv4Og4b1gWf9wOrp7Gfsgi97wDMBIq41dImY3PQjyNt8tk44x+SwuK\nqAxN97DM1fl/Kgl5KKJseSNtaGJcFRUAEPExtOAq8aEE5KW/ckn/U/1xAoGAeeQK\nSJPr+2nPj+zaGwYqPq3myCAkWzcbpFvj4flcVC5vEIayOBnmX97QuV8uP/kZ6gCx\nj44fyfRhfEINc5pucHK83iWO/hn7WtpNtG9gaY1SWy1iGlXUx9sRc6fB0zXGBMMk\n8uPXtS4uiF0ktVTaZyrS8z7syRWmF2q4bIl5q2ECgYAkIQPMyIcqZJOBuo4YhUWU\nIBGVzaVw10a01HXg253ApdQUsOgGvDcB+maSxKSfMma8V2tpTL7ibJ19Nb7hzWqU\nRPNMdpSqL68oYkyi4meoksxP8F9EXRW0tZ8DOGojvc5OpXtGB7xNQiYnL596Gx1b\nIjx/Dv2sYSd2VLD2GDkgFA==\n-----END PRIVATE KEY-----\n";


    private static $notifications = [
        'sendOffer' => ['title'=>'Xəbərdarlıq','content'=>'Hörmətli X0, Sizin X1 nömrəli sifarişiniz üzrə təklif hazırdır. Sifarişlərim bölməsinə nəzər yetirməyinizi xahiş edirik.'],
        'canceledOffer' => ['title'=>'Sifariş ləğv edildi','content'=>'Hörmətli X0, Sizin X1 nömrəli sifarişiniz X2 səbəbdən admin tərəfindən ləğv edildi.'],
        'completedOffer' => ['title'=>'Sifarişiniz tamamlandı','content'=>'Hörmətli X0, Sizin X1 nömrəli sifarişiniz tamamlandı.'],
        'newMessage'   => ['title'=>'Yeni mesaj','content'=>'Hörmətli X0, sizə admindən yeni mesaj var.'],
    ];

    public static function sendFirebaseRequest($data)
    {
        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . self::getAccessToken()
        ];

        $ch = curl_init("https://fcm.googleapis.com/v1/projects/mhmv-47019/messages:send");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    public static function generate($type, $details)
    {
        $res = self::$notifications[$type];

        foreach ($details as $n => $detail) {
            $res['content'] = str_replace("X" . $n, $detail, $res['content']);
        }

        return $res;
    }

    public static function send($type, $details, $userId)
    {
        $model = Customer::query();

        $user = $model->with("parameters")->where('id', $userId)->orderBy("id", 'desc')->first();


        $to = $user->parameters->token;
        $message = self::generate($type, $details);
        $message['customer_id'] = $userId;
        Notification::create($message);

        if ($user->push_notif) {
            $data = [
                "message" => [
                    "token" => $to,
                    "notification" => [
                        "title" => $message['title'],
                        "body" => $message['content']
                    ],
                ]
            ];
            return self::sendFirebaseRequest($data);
        }

        return ['status' => 'success'];
    }

    public static function sendUser($title, $desc, $userId)
    {
        $model = Customer::query();

        $user = $model->with("parameters")->where('id', $userId)->orderBy("id", 'desc')->first();
        $to = $user->parameters->token;

        Notification::create([
            'title' => $title,
            'description' => $desc,
            'customer_id' => $userId
        ]);

        CustomerNotification::create(['title' => $title, 'description' => $desc, 'customer_id' => $userId]);

        if ($user->push_notif) {
            $data = [
                "message" => [
                    "token" => $to,
                    "notification" => [
                        "title" => $title,
                        "body" => $desc
                    ],
                ]
            ];
            return self::sendFirebaseRequest($data);
        }

        return ['status' => 'success'];
    }

    public static function sendAll($title, $desc)
    {
        $custDatas = NotificationParameters::with("customer.parameters")->get()->toArray();


        foreach ($custDatas as $cData) {

            Notification::create([
                'title' => $title,
                'description' => $desc,
                'customer_id' => $cData['user_id']
            ]);

            if (!empty($cData['customer']['parameters']['token'])) {
                $data = [
                    "message" => [
                        "token" => $cData['customer']['parameters']['token'],
                        "notification" => [
                            "title" => $title,
                            "body" => $desc
                        ]
                    ]
                ];

                $response = self::sendFirebaseRequest($data);
            }
        }

    }


    public static function createJWT() {
        $now = time();
        $header = ['alg' => 'RS256', 'typ' => 'JWT'];
        $payload = [
            'iss' => self::$clientEmail,
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            'aud' => 'https://oauth2.googleapis.com/token',
            'exp' => $now + 3600,
            'iat' => $now
        ];

        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(json_encode($header)));
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(json_encode($payload)));

        $signature = '';

        openssl_sign($base64UrlHeader . "." . $base64UrlPayload, $signature, self::$privateKey, 'SHA256');
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    }


    public static function getAccessToken() {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://oauth2.googleapis.com/token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => self::createJWT(),
        ]));

        $headers = ['Content-Type: application/x-www-form-urlencoded'];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);

        return $data['access_token'] ?? null;
    }



}
