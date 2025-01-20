<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\NotificationParameters;
use Illuminate\Http\Request;


class NotificationsController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/notifications",
     *      tags={"Notification"},
     *      summary="Get notifications",
     *      description="Returns api's response",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       )
     *   )
     */
    public function index(Request $request){


        $paginate = $_GET['limit'] ?? null;
        $orderBy = $_GET['orderBy'] ?? null;
        $user_id = $request->user()->id;
        $list = Notification::where("customer_id", $user_id);
        $count = count($list->get());

        if($orderBy!=null){
            $orderBy = explode("_",$orderBy);
            $list = $list->orderBy($orderBy[0],$orderBy[1]);
        }

        if($paginate!=null){
            $list = $list->paginate($paginate);
        }else{
            $list = $list->get();
        }

        return response(['status'=>'success', 'count'=>$count, 'notification'=>$list]);
    }
    /**
     * @OA\Put(
     *      path="/api/notification",
     *      tags={"Notification"},
     *      summary="Update notification read status",
     *      description="Returns api's response",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       )
     *   )
     */
    public function update(Request $request){
        $user_id = $request->user()->id;
        Notification::where("customer_id", $user_id)->update(['read_status'=>1]);

        return response(['status'=>'success']);
    }
    /**
     * @OA\Delete(
     *      path="/api/notification",
     *      tags={"Notification"},
     *      summary="Delete all notifications",
     *      description="Returns api's response",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       )
     *   )
     */
    public function delete(Request $request){
        $user_id = $request->user()->id;
        Notification::where("customer_id", $user_id)->delete();

        return response(['status'=>'success']);
    }
     /**
     * @OA\Post(
     *      path="/api/setParam",
     *      tags={"Notification"},
     *      summary="Set Notification Parameters",
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
    public function setParam(Request $request)
    {
        $validated = $request->validate([
            'notificationType' => 'required|int',
            'token' => 'required|string',
            'deviceId' => 'required|string',
        ]);

        $validated['user_id'] = $request->user()->id;

        try {
            $saveOtp = NotificationParameters::updateOrCreate(['deviceId' => $validated['deviceId'] ], $validated);
            return response(['status'=>'success']);
        }catch (\Exception $exception){
            return response(['status'=>'error','desc'=>$exception]);
        }
    }

    /**
     * @OA\Delete(
     *      path="/api/deleteParam",
     *      tags={"Notification"},
     *      summary="Delete Notification Parameters",
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
    public function deleteParam(Request $request)
    {
        $validated = $request->validate([
            'deviceId' => 'required|string',
        ]);

        try {
            $saveOtp = NotificationParameters::where('deviceId', $validated['deviceId'])->delete();
            return response(['status'=>'success']);
        }catch (\Exception $exception){
            return response(['status'=>'error','desc'=>$exception]);
        }
    }
}
