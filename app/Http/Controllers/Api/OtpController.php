<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\MixHelper;
use App\Http\Helpers\WhatsappHelper;
use App\Http\Requests\Api\OtpRequest;
use App\Models\Api\OtpPhones;
use App\Models\Customer;
use App\Models\Guest;
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
//        try {
            $saveOtp = OtpPhones::updateOrCreate(['phone_number' => $phoneNumber], $parameters);
//            $smsSend = SmsHelper::send($message, $phoneNumber);
            $whatsappSend = WhatsappHelper::send($otp, $phoneNumber);

            return response(['status'=>'success', 'otp'=>$otp, 'deactive_date'=>$deactive_date]);
//        }catch (\Exception $exception){
//            return response(['status'=>'error','desc'=>$exception],403);
//        }
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
            'phoneNumber' => 'required|max:13|unique:guests,phone',
            'otpCode' => 'required|max:4',
            'name' => 'required',
            'subDirectionId' => 'required',
            'isStudent' => 'required']);

        $phoneNumber = str_replace(['+','_',''], '',$validated['phoneNumber']);
        $otpCode = $validated['otpCode'];

        $otpCheck = OtpPhones::where(["phone_number" => $phoneNumber, 'otp_code'=>$otpCode])->where("deactive_date", ">", (string)date("Y-m-d H:i:s"))->first();

        if($otpCheck)
        {
            $data['phone'] = $phoneNumber;
            $data['sub_direction_id'] = $validated['subDirectionId'];
            $data['name'] = $validated['name'];
            $data['is_student'] = $validated['isStudent'];
            $customer = Guest::create($data);
            $token = $customer->createToken('token_name')->plainTextToken;

            return response(['status'=>'success', 'payStatus'  => true, 'token'=>$token, 'user'=>$customer]);
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
            $user = Guest::where("phone",$phoneNumber)->first();
            if(isset($user->tokens)) $user->tokens()->delete();
            $token = $user->createToken('token_name')->plainTextToken;
            return response(['status'=>'success', 'payStatus'=> true,'token'=>$token, 'user'=>$user]);
        }else{
            return response(['status'=>'error', 'desc'=>'Wrong OTP code'], 403);
        }
    }

}
