<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginApiRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Models\Customer;
use App\Models\Register;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    /**
     * @OA\Put(
     *      path="/api/updateUserData",
     *      tags={"Auth"},
     *      summary="Register user's other Data (stage 3)",
     *      description="Returns api's response",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Parameter(
     *         name="name",
     *         in="path",
     *         description="example: Jack",
     *         required=true,
     *      ),
     *      @OA\Parameter(
     *         name="surname",
     *         in="path",
     *         description="example: Sparrow",
     *         required=true,
     *      ),
     *      @OA\Parameter(
     *         name="email",
     *         in="path",
     *         description="example: Baker str. 221B",
     *         required=true,
     *      ),
     *   )
     */
    public function updateUserData(Request $request)
    {
        $parameters = $request->validate([
            'name' => 'required',
            'email'=>'required|email',
            'class'=>'required',
            'lesson'=>'required'
        ]);
        
        $result = $request->user()->update($parameters);
        if ($result){
            return response(['status'=>'success', 'user'=>$request->user()]);
        }else{
            return response(['status'=>'error','desc'=>'There was problem. Please contact support.'],403);
        }
    }

    /**
     * @OA\Post(
     *      path="/api/login",
     *      tags={"Auth"},
     *      summary="Register user's other Data (stage 4)",
     *      description="Returns api's response",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *
     *      @OA\Parameter(
     *         name="username",
     *         in="path",
     *         description="example: user1",
     *         required=true,
     *      ),
     *     @OA\Parameter(
     *         name="password",
     *         in="path",
     *         description="example: 12345678",
     *         required=true,
     *      ),
     *
     *   )
     */
    public function login(LoginApiRequest $request){
        $request = $request->validated();
        
        $user = Customer::where("username",$request['userName'])->first();
        
        if ($user && Hash::check($request['password'], $user->password)) {
    
            $param = [
                'deviceId' => $request['deviceId'],
                'customerId' => $user['id']
            ];
            
            if ($user->username !== 'user350') {
                $deviceCheck = DB::select("SELECT * FROM device_log WHERE customer_id = :customerId", [
                    'customerId' => $user['id']
                ]);
        
                if (!$deviceCheck) {
                    DB::insert("INSERT into device_log (device_id, customer_id) values (:deviceId, :customerId)", $param);
                } else {
                    if ($deviceCheck[0]->device_id != $request['deviceId']) {
                        return response(['status' => 'error', 'desc' => 'Bu istifadəçi başqa bir cihazla artıq daxil olub.'], 403);
                    }
                }
            }
            
            $user->tokens()->delete();
            $token = $user->createToken('token_name')->plainTextToken;
            
            return response(['status' => 'success', 'payStatus' => true, 'token' => $token, 'user' => $user]);
        
        } else {
            return response(['status' => 'error', 'payStatus' => true, 'desc' => 'Giriş məlumatları düzgün deyil!'], 403);
        }

    }
    
    public function registerRequest(Request $request)
    {
        $request = $request->validate([
            'userName' => 'required',
            'email'=>'required|email',
            'class'=>'required',
            'lesson'=>'required'
        ]);

        $result = Register::create([
            'username'=>$request['userName'],
            'email'=>$request['email'],
            'class'=>$request['class'],
            'lesson'=>$request['lesson']
        ]);
        
        
         if($result){
            return response(['status'=>'success', 'result'=>$result]);
        }else{
            return response(['status'=>'error', 'desc'=>'These credentials do not match our records'], 403);
        }
        
        
    }
    

    /**
     * @OA\Put(
     *      path="/api/updatePassword",
     *      tags={"Auth"},
     *      summary="Update user password",
     *      description="Returns api's response",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *
     *      @OA\Parameter(
     *         name="password",
     *         in="path",
     *         description="example: 12345678",
     *         required=true,
     *      ),
     *     @OA\Parameter(
     *         name="password_confirmation",
     *         in="path",
     *         description="example: 12345678",
     *         required=true,
     *      ),
     *
     *   )
     */
    public function updatePassword(Request $request){
        $validated = $request->validate([
            'password' => 'required|confirmed|string|min:8|max:55',
            'password_confirmation' => 'required|string|min:8|max:55',
        ]);

        $result = $request->user()->update(['password'=>Hash::make($validated['password'])]);
        if($result){
            return response(['status'=>'success', 'payStatus'  => true, 'user'=>$request->user()]);
        }else{
            return response(['status'=>'error', 'desc'=>'These credentials do not match our records'], 403);
        }
    }

    /**
     * @OA\Post(
     *      path="/api/uploadImage",
     *      tags={"Upload user profil image"},
     *      summary="Upload user profil image",
     *      description="Returns api's response",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *
     *      @OA\Parameter(
     *         name="image",
     *         in="path",
     *         description="type: file",
     *         required=true,
     *      ),
     *
     *
     *   )
     */
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|file',
        ]);

        $image = $request->file('image');
        $ext = pathinfo($image->getClientOriginalName(), PATHINFO_EXTENSION);
        $new_img = 'img_'.time().'.'.$ext;
        if($image->move(public_path("postImage/"), $new_img)){
            $data['image'] = "postImage/".$new_img;
            $result = $request->user()->update($data);
            return response(['status'=>'success', 'request'=>$result],200);
        }else{
            return response(['status'=>'error', 'desc'=>"Image couldn't upload"],403);
        }
        
    }
    
     /**
     * @OA\Delete(
     *      path="/api/userDelete",
     *      tags={"Auth"},
     *      summary="Delete user",
     *      description="Returns api's response",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *
     *   )
     */
    public function userDelete(Request $request)
    {
        $result = $request->user()->delete();
        if ($result)
        {
            return response(['status'=>"success"]);
        }
    }



    /**
     * @OA\Post(
     *      path="/api/logout",
     *      tags={"Auth"},
     *      summary="Logout user",
     *      description="Returns api's response",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *
     *   )
     */
    public function logout(Request $request)
    {
        $result = $request->user()->currentAccessToken()->delete();
        if ($result)
        {
            return response(['status'=>"success"]);
        }
    }
    
     /**
     * @OA\Get(
     *      path="/api/userDetails",
     *      tags={"Auth"},
     *      summary="User details",
     *      description="Returns api's response",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *
     *   )
     */
    public function userDetails(Request $request)
    {
         $groups = Group::whereIn("id",json_decode($request->user()->group_ids,1))->get();

        //  $newP = array_map(function($pack) use($request){
             
        //      $pack['users'] = in_array($request->user()->id, array_column($pack['users'],'id'));
             
        //      return $pack;
        //  },$packages);
         
         $userData = $request->user();
         
         $userData['groups'] = $groups;

         
        return response(['status'=>"success", "user"=>$userData]);
        
    }
}
