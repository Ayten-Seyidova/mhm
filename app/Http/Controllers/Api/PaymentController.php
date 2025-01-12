<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\User;
use http\Env\Response;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Package;
use Carbon\Carbon;

class PaymentController extends Controller
{
    private $testMerchantId = 456;
    private $testUrl = 'https://pay-dev.pulpal.az/payment';

    private $prodMerchantId = 3596;
    private $prodUrl = 'https://pay.pulpal.az/payment';

    private $salt = '';
    private $repeatable = 'true';

    public $merchantId = '';
    public $gatewayUrl = '';
    public $env = 'production';
//   public $env = 'test';
    public $epochTime = '';
    public $externalId;
    public $price;
    public $nameAz = '';
    public $nameEn = '';
    public $nameRu = '';
    public $descAz = '';
    public $descEn = '';
    public $descRu = '';

    public function __construct()
    {
        //$this->env = app()->environment();
        $this->epochTime = $this->getEpochTime();

        if ($this->env == 'production') {
            $this->merchantId = $this->prodMerchantId;
            $this->gatewayUrl = $this->prodUrl;
        } else {
            $this->merchantId = $this->testMerchantId;
            $this->gatewayUrl = $this->testUrl;
        }
    }

    private function signature()
    {
        $signature = $this->nameEn
            . $this->nameAz
            . $this->nameRu
            . $this->descEn
            . $this->descRu
            . $this->descAz
            . $this->merchantId
            . $this->externalId
            . $this->price
            . $this->epochTime
            . $this->salt;

        return sha1($signature);
    }


    private function apiParams()
    {
        $params = [
            'merchantId' => $this->merchantId,
            'price' => $this->price,
            'repeatable' => $this->repeatable,
            'name_az' => $this->nameAz,
            'name_ru' => $this->nameRu,
            'name_en' => $this->nameEn,
            'description_en' => $this->descEn,
            'description_ru' => $this->descRu,
            'description_az' => $this->descAz,
            'externalId' => $this->externalId,
            'signature2' => $this->signature(),
        ];

        return $params;
    }

    public function getUrl(Request $request)
    {
        
        $userInfo = Customer::find($request->id);
        $packageInfo = Package::find($request->package_id);
        
        return "https://wa.me/994514551187?text=".urlencode("Ad soyad: $userInfo->name $userInfo->surname, Nömrə: $userInfo->phone, $packageInfo->package_name ($packageInfo->price AZN) adlı paket alışı etmək istəyirəm.");
        
        /*
        $request->name = $request->price . " AZN Abunəlik ödənişi";

        $this->externalId = $request->id;
        $this->price = $request->price * 100;
        $this->nameAz = $request->name;
        $this->nameEn = $request->name;
        $this->nameRu = $request->name;
        $this->descAz = $request->name;
        $this->descEn = $request->name;
        $this->descRu = $request->name;

        $params = $this->apiParams();

        return $this->gatewayUrl . '?lang=az&' . http_build_query($params);
        
        */
    }


    public function getEpochTime()
    {
        return intval((time() * 1000) / 300000);
    }


    public function PaymentDelivery()
    {

        $json = file_get_contents('php://input');


        $array = json_decode($json, true);

        $userStatus = Customer::where('id', '=', $array['ExternalId']);
        $paid_amount_pulpal = $array['Price'] / 100;

        if (!empty($userStatus)) {

            Payment::create([
                'price' => $paid_amount_pulpal,
                'debt' => $array['Debt'],
                'currency' => $array['Currency'],
                'user_id' => $array['ExternalId'],
                'PaymentAttempt' => $array['PaymentAttempt'],
                'State' => json_encode($array['State']),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            Notification::create([
                'customer_id' => $array['ExternalId'],
                'title' => 'Abunəliyiniz uğurla aktivləşdirildi',
                'content' => $paid_amount_pulpal . ' AZN məbləğində ödəniş etdiniz və 1 illik abunəliyiniz uğurla aktivləşdirildi. Təşəkkür edirik!',
                'read_status' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            Subscription::create([
                'user_id' => $array['ExternalId'],
                'paid_amount' => $paid_amount_pulpal,
                'last_date' => Carbon::now()->addYears(1),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            return response(['status'=>'success'],200);


        } else {

            Payment::create([
                'price' => $paid_amount_pulpal,
                'debt' => $array['Debt'],
                'currency' => $array['Currency'],
                'user_id' => $array['ExternalId'],
                'PaymentAttempt' => $array['PaymentAttempt'],
                'State' => json_encode($array['State']),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            return response(['status'=>'error'],403);

        }

    }


    public function End()
    {
        //?Status=canceled&ExternalId=16
        $user = Customer::find($_GET['ExternalId']);
        $data['name'] = $user->name . ' ' . $user->surname;
        return view('other.payment_end')
            ->with('data', $data);
    }
}
