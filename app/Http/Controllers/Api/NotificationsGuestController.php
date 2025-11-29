<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GuestNotification;
use App\Models\NotificationParametersGuest;
use Illuminate\Http\Request;


class NotificationsGuestController extends Controller
{
//    public function index(Request $request)
//    {
//        $paginate = $_GET['limit'] ?? null;
//        \Log::error('1');
//
//        $orderBy = $_GET['orderBy'] ?? null;
//        \Log::error('2');
//        //$user_id = $request->user()->id;
//        // $list = GuestNotification::where("guest_id", $user_id);
//        $list = GuestNotification::where("read_status", 0);
//        \Log::error('3');
//
//        //$count = count($list->get());
//        $count = 5;
//        \Log::error('4');
//
//        if ($orderBy != null) {
//            \Log::error('5');
//            $orderBy = explode("_", $orderBy);
//            $list = $list->orderBy($orderBy[0], $orderBy[1]);
//        }
//
//        if ($paginate != null) {
//            \Log::error('6');
//            $list = $list->paginate($paginate);
//        } else {
//            \Log::error('7');
//            $list = $list->get();
//        }
//        \Log::error('8');
//
//        return response(['status' => 'success', 'count' => $count, 'notification' => $list]);
//    }

    public function index(Request $request)
    {
        // Request parametrləri adam kimi alınır
        $paginate = $request->query('limit');
        $orderBy  = $request->query('orderBy');

        // Base query
        $query = GuestNotification::where('read_status', 0);

        $totalCount = $query->count();

        if ($orderBy) {
            $parts = explode("_", $orderBy);

            if (count($parts) === 2) {
                $column = $parts[0];
                $direction = strtolower($parts[1]) === 'desc' ? 'desc' : 'asc';

                $query->orderBy($column, $direction);
            }
        }

        $notifications = $paginate
            ? $query->paginate($paginate)
            : $query->get();

        return response([
            'status'        => 'success',
            'count'         => $totalCount,
            'notification'  => $notifications
        ]);
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
