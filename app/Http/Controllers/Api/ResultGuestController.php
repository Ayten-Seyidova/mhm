<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Guest;
use App\Models\GuestResult;
use App\Models\Result;
use Illuminate\Http\Request;

class ResultGuestController extends Controller
{

    public function getResultGuest(Request $request){
     $paginate = $request->limit ?? null;
     $orderBy = $request->orderBy ?? null;

     $model = GuestResult::class;

     $list = $model::with('guestExam')->where("guest_id", $request->user()->id);

      if($orderBy!=null){
          $orderBy = explode("_",$orderBy);
          $list = $list->orderBy($orderBy[0],$orderBy[1]);
      }

      if($paginate!=null){
          $list = $list->paginate($paginate);
      }else{
          $list = $list->get();
      }

     return response(['status' => 'success', 'list' => $list]);
    }

   public function setResultGuest(Request $request){
        $examId = $request->examId;
        $correctCount = $request->correctCount;
        $incorrectCount = $request->incorrectCount;
        $time = $request->time;
        $point = $request->point;
        $model = GuestResult::class;



        $result = $model::create([
                'time'=>$time,
                'point'=>$point,
                'correct_count'=>$correctCount,
                'incorrect_count'=>$incorrectCount,
                'guest_exam_id'=>$examId,
                'guest_id'=>$request->user()->id
            ]);

        return response(['status' => 'success', 'result' => $result]);
    }
}
