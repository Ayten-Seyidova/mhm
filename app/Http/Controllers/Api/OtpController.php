<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\MixHelper;
use App\Http\Requests\Api\OtpRequest;
use App\Models\Api\OtpPhones;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Helpers\SmsHelper;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;


class OtpController extends Controller
{

    /**
     * @OA\Post(
     *      path="/api/sendOtp",
     *      tags={"Auth"},
     *      summary="Send OTP (stage 1 or update user's phone)",
     *      description="Returns api's response",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *
     *      @OA\Parameter(
     *         name="phoneNumber",
     *         in="path",
     *         description="example: 994551234567",
     *         required=true,
     *      ),
     *
     *   )
     */
    public function sendOtp(OtpRequest $request)
    {
        $validated = $request->all();
        $phoneNumber = $validated['phoneNumber'];

        $otp = rand(1000,9999);
        $message = "OTP code: ".$otp;
        $deactive_date = date("Y-m-d H:i:s", strtotime("+10 minutes"));

        $parameters = [
            'phone_number'=>$phoneNumber,
            'otp_code'=>$otp,
            'deactive_date'=>$deactive_date
        ];

        try {
            $saveOtp = OtpPhones::updateOrCreate(['phone_number' => $phoneNumber], $parameters);
            $smsSend = SmsHelper::send($message, $phoneNumber);
            return response(['status'=>'success', 'deactive_date'=>$deactive_date]);
        }catch (\Exception $exception){
            return response(['status'=>'error','desc'=>$exception],403);
        }
    }

    /**
     * @OA\Post(
     *      path="/api/checkOtp",
     *      tags={"Auth"},
     *      summary="Check OTP for Register user (stage 2)",
     *      description="Returns api's response",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *
     *      @OA\Parameter(
     *         name="phoneNumber",
     *         in="path",
     *         description="example: 994551234567",
     *         required=true,
     *      ),
     *      @OA\Parameter(
     *         name="otpCode",
     *         in="path",
     *         description="example: 1111",
     *         required=true,
     *      ),
     *     @OA\Parameter(
     *         name="email",
     *         in="path",
     *         description="example: info@gmail.com",
     *         required=true,
     *      ),
     *     @OA\Parameter(
     *         name="name",
     *         in="path",
     *         description="example: Jack",
     *         required=true,
     *      ),
     *     @OA\Parameter(
     *         name="surname",
     *         in="path",
     *         description="example: Sparrow",
     *         required=true,
     *      ),
     *     @OA\Parameter(
     *         name="password",
     *         in="path",
     *         description="example: 123456",
     *         required=true,
     *      ),
     *
     *   )
     */
    public function checkOtpRegister(Request $request)
    {
        $validated = $request->validate([
            'phoneNumber' => 'required|max:13|unique:customers,phone',
            'otpCode' => 'required|max:4',
            'email' => 'required|email',
            'name' => 'required',
            'surname' => 'required',
            'deviceId' => 'required',
            'password' => 'required|min:8']);

        $phoneNumber = str_replace(['+','_',''], '',$validated['phoneNumber']);
        $otpCode = $validated['otpCode'];

        $otpCheck = OtpPhones::where(["phone_number" => $phoneNumber, 'otp_code'=>$otpCode])->where("deactive_date", ">", (string)date("Y-m-d H:i:s"))->first();

        if($otpCheck)
        {
//            $data = [];
            $data['phone'] = $phoneNumber;
            $data['password'] = Hash::make($validated['password']);
            $data['email'] = $validated['email'];
            $data['name'] = $validated['name'];
            $data['surname'] = $validated['surname'];
            $customer = Customer::create($data);
            $token = $customer->createToken('token_name')->plainTextToken;
            
            $param = [
                'deviceId' => $validated['deviceId'],
                'customerId' => $customer->id
            ];
            
            DB::insert("INSERT into device_log (device_id,customer_id) values (:deviceId,:customerId)", $param);

            return response(['status'=>'success', 'payStatus'  => true, 'token'=>$token, 'data'=>$customer]);
        }else{
            return response(['status'=>'error', 'desc'=>'Wrong OTP code'], 403);
        }
    }

    /**
     * @OA\Put(
     *      path="/api/checkOtpUpdate",
     *      tags={"Auth"},
     *      summary="Check OTP for Update user data (phone)",
     *      description="Returns api's response",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *
     *      @OA\Parameter(
     *         name="phoneNumber",
     *         in="path",
     *         description="example: 994551234567",
     *         required=true,
     *      ),
     *      @OA\Parameter(
     *         name="otpCode",
     *         in="path",
     *         description="example: 1111",
     *         required=true,
     *      ),
     *
     *   )
     */
    public function checkOtpUpdate(Request $request)
    {
        $validated = $request->validate([
            'phoneNumber' => 'required|max:13',
            'otpCode' => 'required|max:4'
        ]);

        $phoneNumber = str_replace(['+','_',''], '',$validated['phoneNumber']);
        $otpCode = $validated['otpCode'];

        $otpCheck = OtpPhones::where(["phone_number" => $phoneNumber, 'otp_code'=>$otpCode])->where("deactive_date", ">", date("Y-m-d H:i:s"))->first();

        if($otpCheck)
        {
            $customer = $request->user()->update(['phone'=>$phoneNumber]);

            return response(['status'=>'success', 'user'=>$customer]);
        }else{
            return response(['status'=>'error', 'desc'=>'Wrong OTP code'], 403);
        }
    }

    /**
     * @OA\Put(
     *      path="/api/checkOtpLogin",
     *      tags={"Auth"},
     *      summary="For forgot password service",
     *      description="Returns api's response",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *
     *      @OA\Parameter(
     *         name="phoneNumber",
     *         in="path",
     *         description="example: 994551234567",
     *         required=true,
     *      ),
     *      @OA\Parameter(
     *         name="otpCode",
     *         in="path",
     *         description="example: 1111",
     *         required=true,
     *      ),
     *
     *   )
     */
    public function checkOtpLogin(Request $request)
    {
        $validated = $request->validate([
            'phoneNumber' => 'required|max:13',
            'otpCode' => 'required|max:4'
        ]);

        $phoneNumber = str_replace(['+','_',''], '',$validated['phoneNumber']);
        $otpCode = $validated['otpCode'];

        $otpCheck = OtpPhones::where(["phone_number" => $phoneNumber, 'otp_code'=>$otpCode])->where("deactive_date", ">", date("Y-m-d H:i:s"))->first();

        if($otpCheck)
        {
            $user = Customer::where("phone",$phoneNumber)->first();
            $user->tokens()->delete();
            $token = $user->createToken('token_name')->plainTextToken;
            return response(['status'=>'success', 'payStatus'=> true,'token'=>$token, 'user'=>$user]);
        }else{
            return response(['status'=>'error', 'desc'=>'Wrong OTP code'], 403);
        }
    }

}
