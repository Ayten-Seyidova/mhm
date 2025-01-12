<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\Setting;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/subscriptionByCustomer",
     *      tags={"Subscription"},
     *      summary="Get subscription",
     *      description="Returns api's response",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       )
     *   )
     */
    public function index(Request $request){
        $result = Subscription::where('user_id', $request->user()->id)->get();
        $price = number_format(Setting::first()->price, 2);

        return response(['status'=>'success', 'price'=>$price, 'subscription'=>$result]);
    }
}
