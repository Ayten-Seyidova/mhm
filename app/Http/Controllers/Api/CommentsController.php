<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
   public function setComment(Request $request){
        $comment = $request->comment;
        $videoCourseId = $request->videoCourseId;
        $rate= $request->rate;
        
        $result = Comment::create([
                'description'=>$comment,
                'video_course_id'=>$videoCourseId,
                'rate'=>$rate,
                'customer_id'=>$request->user()->id
            ]);
            
        return response(['status' => 'success', 'result' => $result]);
    }
}
