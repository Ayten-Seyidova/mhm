<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GuestNotification;
use App\Models\NotificationParametersGuest;
use Illuminate\Http\Request;


class NotificationsGuestController extends Controller
{
    public function index(Request $request)
    {
        $paginate = $_GET['limit'] ?? null;

        $orderBy = $_GET['orderBy'] ?? null;
        //$user_id = $request->user()->id;
        // $list = GuestNotification::where("guest_id", $user_id);
        $list = GuestNotification::where("all", true);

        $count = count($list->get());

        if ($orderBy != null) {
            $orderBy = explode("_", $orderBy);
            $list = $list->orderBy($orderBy[0], $orderBy[1]);
        }

        if ($paginate != null) {
            $list = $list->paginate($paginate);
        } else {
            $list = $list->get();
        }
        return response(['status' => 'success', 'count' => $count, 'notification' => $list]);
    }


    public function update(Request $request)
    {
      //  $user_id = $request->user()->id;
       // GuestNotification::where("guest_id", $user_id)->update(['read_status' => 1]);

        return response(['status' => 'success']);
    }

    public function delete(Request $request)
    {
        $user_id = $request->user()->id;
        GuestNotification::where("guest_id", $user_id)->delete();

        return response(['status' => 'success']);
    }

    public function setParam(Request $request)
    {
        $validated = $request->validate([
            'notificationType' => 'required|int',
            'token' => 'required|string',
            'deviceId' => 'required|string',
        ]);

        $validated['user_id'] = $request->user()->id;

        try {
            NotificationParametersGuest::where('token', $validated['token'])->delete();

            $saveOtp = NotificationParametersGuest::updateOrCreate(['deviceId' => $validated['deviceId']], $validated);
            return response(['status' => 'success']);
        } catch (\Exception $exception) {
            return response(['status' => 'error', 'desc' => $exception]);
        }
    }


    public function deleteParam(Request $request)
    {
        $validated = $request->validate([
            'deviceId' => 'required|string',
        ]);

        try {
            $saveOtp = NotificationParametersGuest::where('deviceId', $validated['deviceId'])->delete();
            return response(['status' => 'success']);
        } catch (\Exception $exception) {
            return response(['status' => 'error', 'desc' => $exception]);
        }
    }
}
