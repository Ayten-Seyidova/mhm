<?php


namespace App\Http\Helpers;
use App\Models\Customer;
use App\Models\CustomerNotification;
use App\Models\NotificationParameters;
use App\Models\Notification;


class FirebaseHelper
{

    private static $key = "AAAAJN7PHmE:APA91bHC3XlBcqnxU1et9mp5gzH8q2WXDtpWfDtEI2NDWVxV02dCygT49PS0oYwvfn8I25Q6YBebNRTus7Jw9jHf22kzPqHETqgKNI9-MEnzZUHAJ4pO51UXraFmbo4WV5yr5G9_1vab";

    private static $notifications = [
            'balance' => ['title'=>'Balansınız artırıldı','content'=>'Balansınız X0 məbləğ artırıldı. Tətbiqimizdən istifadə etdiyiniz üçün təşəkkür edirik!'],
            'answer'     => ['title'=>'Cavablar hazırdır','content'=>'X0 üzrə cavabınız hazırdır. Tətbiqdə yoxlaya bilərsiniz!']
        ];


    public static function sendFirebaseRequest($data)
    {
        $headers = [
            'Content-Type: application/json',
            'Authorization: key='.self::$key
        ];

        $ch = curl_init("https://fcm.googleapis.com/fcm/send");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    public static function generate($type,$details){
        $res = self::$notifications[$type];

        foreach($details as $n => $detail){
        	$res['content'] = str_replace("X".$n, $detail, $res['content']);
        }

        return $res;
    }

    public static function send($type,$details,$userId)
    {
        $user = Customer::with("parameters")->where('id',$userId)->orderBy("id",'desc')->first();
        if($user->parameters) {
           $to = $user->parameters->token;
            $message = self::generate($type,$details);
            $message['user_id'] = $userId;
            $message['customer_id'] = $userId;
            Notification::create($message);

            file_put_contents(public_path("storage/").'abc.txt',json_encode([$type,$details,$userId]));

            $data = [
                "to"=>$to,
                "notification"=>[
                    "title"=>$message['title'],
                    "body"=>$message['content']
                ]
            ];

            return self::sendFirebaseRequest($data);
        }
    }


     public static function sendUser($title,$desc,$userId)
    {

        $model = Customer::query();

        $user = $model->with("parameters")->where('id',$userId)->orderBy("id",'desc')->first();
        // dd($userId);
        $to = $user->parameters->token;
         Notification::create(['title'=>$title, 'content'=>$desc,  'customer_id'=>$userId ]);

        if($user->push_notif){
          $data = [
                "to"=>$to,
                "notification"=>[
                    "title"=>$title,
                    "body"=>$desc
                ]
            ];
            return self::sendFirebaseRequest($data);
        }

        return ['status'=>'success'];

    }


    public static function sendAll($title,$desc)
    {
        $custDatas = NotificationParameters::with("customer")
        // ->whereHas('customer', function($q) use ($status){
        //         $q->where('status', $status);
        //     })
        ->get()
        ->toArray();

        $tokens = [];
        foreach($custDatas as $cData){
            Notification::create(['title'=>$title, 'content'=>$desc, 'customer_id'=>$cData['user_id']]);
            // if($cData['customer']['push_notif']){
                $tokens[]=$cData['token'];
            // }
        }

        // dd($tokens);

        $data = [
            "registration_ids"=>$tokens,
            "notification"=>[
                "title"=>$title,
                "body"=>$desc
            ]
        ];

        return self::sendFirebaseRequest($data);
    }

    public static function sendUserCustomer($title, $desc, $userId)
    {
        $model = Customer::query();
        $user = $model->with("parameters")->where('id', $userId)->orderBy("id", 'desc')->first();
        if (isset($user->parameters->token)) {
            $to = $user->parameters->token;
            CustomerNotification::create(['title' => $title, 'description' => $desc, 'customer_id' => $userId]);

            $data = [
                "to" => $to,
                "notification" => [
                    "title" => $title,
                    "body" => $desc
                ]
            ];
            return self::sendFirebaseRequest($data);
        }
    }


    public static function sendAllCustomer($title, $desc)
    {
        $custDatas = NotificationParameters::with("customer")->whereHas('customer', function ($q) {})->get()->toArray();

        $tokens = [];
        foreach ($custDatas as $cData) {
            CustomerNotification::create(['title' => $title, 'description' => $desc, 'customer_id' => $cData['user_id']]);
            $tokens[] = $cData['token'];
        }

        $data = [
            "registration_ids" => $tokens,
            "notification" => [
                "title" => $title,
                "body" => $desc
            ]
        ];

        return self::sendFirebaseRequest($data);
    }
}
