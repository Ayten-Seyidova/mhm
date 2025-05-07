<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Book;
use App\Models\Direction;
use App\Models\GuestComment;
use App\Models\GuestExam;
use App\Models\Lesson;
use App\Models\OurTeacher;
use App\Models\Post;
use App\Models\PostLike;
use App\Models\Story;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function posts(Request $request){
        $paginate = $request->limit ?? null;
        $orderBy = $request->orderBy ? explode("_",$request->orderBy) : ['id','desc'];
        $result = Post::with(["subDirection", "variants","teacher","comments", "likes"])->withCount(["comments","likes"])
            ->where('status', 1)
            ->where('sub_direction_id', $request->user()->sub_direction_id);

        if($request->type){
            $result= $result->where('type',$request->type);
        }

        $result = $result->orderBy($orderBy[0],$orderBy[1]);


        if($paginate!=null){
            $result = $result->paginate($paginate);
        }else{
            $result = $result->get();
        }

        return response(['status'=>'success', 'data'=>$result]);
    }

    public function answer(Request $request){
        $result = Answer::with(['post','guest'])->create(['post_id'=>$request->postId, 'answer'=>$request->answer, 'guest_id'=>$request->user()->id]);

        return response(['status'=>'success', 'data'=>$result]);
    }

    public function sendCommentByPost(Request $request)
    {
        $result = GuestComment::with(['post','guest'])->create(['post_id'=>$request->postId, 'comment'=>$request->comment, 'guest_id'=>$request->user()->id]);

        return response(['status'=>'success', 'data'=>$result]);
    }

    public function deleteComment(Request $request)
    {
        $result = GuestComment::where("id",$request->commentId)->delete();

        return response(['status'=>'success', 'data'=>$result]);
    }

    public function setLikeByPost(Request $request)
    {
        $model = PostLike::query();
        $checkLike = $model->where(['post_id'=>$request->postId, 'guest_id'=>$request->user()->id]);
        $result = $checkLike->first()?$checkLike->delete():$model->create(['post_id'=>$request->postId, 'guest_id'=>$request->user()->id]);

        return response(['status'=>'success', 'data'=>$result]);
    }

    public function getCommentsByPost(Request $request)
    {
        $result = GuestComment::with(['post','guest'])
            ->where('post_id',$request->postId)
            ->orderBy('created_at','desc')
            ->paginate(10);

        return response(['status'=>'success', 'data'=>$result]);
    }

    public function directions(Request $request){
        $result = Direction::with('subDirections')->where('is_deleted',0)->get();

        return response(['status'=>'success', 'data'=>$result]);
    }

    public function stories(Request $request){
        $result = Story::get();

        return response(['status'=>'success', 'data'=>$result]);
    }

    public function guestExam(Request $request){
        $paginate = $request->limit ?? null;
        $orderBy = $request->orderBy ?? null;
        $teacherId = $request-> teacherId ?? null;
        $list = GuestExam::with('questions')
            ->withCount('questions')
            ->where('status',1)
            ->where("is_deleted",0)
            ->where('user_id', $teacherId)
            ->whereHas('guestExamSubDirections',function($q) use($request){
                $q->where('sub_direction_id', $request->user()->sub_direction_id);
            });

        if($orderBy!=null){
            $orderBy = explode("_",$orderBy);
            $list = $list->orderBy($orderBy[0],$orderBy[1]);
        }

        if($paginate!=null){
            $list = $list->paginate($paginate);
        }else{
            $now = Carbon::now();
            $list = $list->where('time',"!=",null)
                ->where('time', '>', $now)
                ->orderBy('time', 'asc')->first();
        }

        return response(['status' => 'success', 'list' => $list]);
    }

    public function teachers(Request $request){
        $result = User::where('type','teacher')
            ->where('status', 1)
            ->whereHas('subDirection',function ($q) use($request) {
                $q->where('sub_direction_id',$request->user()->sub_direction_id);
            })->get();

        return response(['status'=>'success', 'data'=>$result]);
    }

    public function ourTeachers(Request $request){
        $result = OurTeacher::get();

        return response(['status'=>'success', 'data'=>$result]);
    }

    public function lessons(Request $request){
        $result = Lesson::with("subDirection")
            ->where('status', 1)
            ->where('sub_direction_id', $request->user()->sub_direction_id)->get();

        return response(['status'=>'success', 'data'=>$result]);
    }

    public function books(Request $request){
        $result = Book::where('status', 1)->get();

        return response(['status'=>'success', 'data'=>$result]);
    }
}
