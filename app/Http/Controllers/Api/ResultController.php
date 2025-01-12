<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Result;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    
    public function getResult(Request $request){
     $paginate = $request->limit ?? null;
     $orderBy = $request->orderBy ?? null;

     $list = Result::with('exam')->where('customer_id', $request->user()->id);

    //   dd($list->toSql());
      
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
    
   public function setResult(Request $request){
        $examId = $request->examId;
        $correctCount = $request->correctCount;
        $incorrectCount = $request->incorrectCount;
        $time = $request->time;
        
        $result = Result::create([
                'time'=>$time,
                'correct_count'=>$correctCount,
                'incorrect_count'=>$incorrectCount,
                'exam_id'=>$examId,
                'customer_id'=>$request->user()->id
            ]);
            
        return response(['status' => 'success', 'result' => $result]);
    }
}
