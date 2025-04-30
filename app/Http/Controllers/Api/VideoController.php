<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VideoCourse;
use App\Models\Exam;
use App\Models\Api\VideoDone;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function videoCourses($type,Request $request){
         $paginate = $_GET['limit'] ?? null;
         $orderBy = $_GET['orderBy'] ?? null;
         $searchKey = $_GET['searchKey'] ?? null;
         $groupParams = @$request->groups;
         $groupAdded = @$request->user()->groupAdded->toArray();
         $videoCourses = VideoCourse::selectRaw('*,
       IFNULL((select ceil(SUM(rate) / count(customer_id))
            from comments
            where video_course_id = video_courses.id
            group by video_course_id),0) as raiting_sum_point,
       IFNULL((select count(id)
            from comments
            where video_course_id = video_courses.id
            group by video_course_id),0) as raiting_count,
       IFNULL((select count(id)
            from videos
            where subject_id in ( select id from subjects where video_course_id = video_courses.id and is_deleted=0 and status=1) and is_deleted=0
            ),0) AS video_count,
            IFNULL((select count(id)
            from subjects
            where video_course_id = video_courses.id and is_deleted=0 and status=1
            group by video_course_id),0) as subjects_count
       ')->where('type',$type)
         ->where('is_deleted',0)->where('status',1)
         ->with(['groupsFilter','groups','comments.customer','subjects.videos'])
         ->whereHas('groupsFilter', function ($q) use ($groupAdded) {
            $q->where(function ($subQuery) use ($groupAdded) {
                foreach ($groupAdded as $index => $group) {
                    $method = $index === 0 ? 'where' : 'orWhere';
                    $subQuery->$method(function ($query) use ($group) {
                        $query->where('group_id', $group['group_id']);
                        if (!empty($group['end_date'])) {
                            $query->where('created_at', '<=', $group['end_date']);
                        }
                        if (!empty($group['date'])) {
                            $query->where('created_at', '>=', $group['date']);
                        }
                    });
                }
            });
        });


          if($searchKey!=null){
              $videoCourses = $videoCourses
                                ->where('name',"like", "%".$searchKey."%");
          }

          if(isset($groupParams)){
              $videoCourses = $videoCourses->where(function ($query) use ($groupParams) {
                    foreach ($groupParams as $groupParam) {
                        $query->orWhere('group_ids', 'like', '%"'.$groupParam.'"%');
                    }
                });
          }

          $videoCourses = $videoCourses->has('groups');

          if($orderBy!=null){
              $orderBy = explode("_",$orderBy);
              $videoCourses = $videoCourses->orderBy($orderBy[0],$orderBy[1]);
          }

          if($paginate!=null){
              $videoCourses = $videoCourses->paginate($paginate);
          }else{
              $videoCourses = $videoCourses->get();
          }

         return response(['status' => 'success', 'videoCourses' => $videoCourses]);
    }

     public function myVideoCourses($type,Request $request){
         $paginate = $_GET['limit'] ?? null;
         $orderBy = $_GET['orderBy'] ?? null;
         $searchKey = $_GET['searchKey'] ?? null;
         $groupParams = @$request->groups;
         $groupAdded = @$request->user()->groupAdded->toArray();
         $videoCourses = VideoCourse::selectRaw('*,
       IFNULL((select ceil(SUM(rate) / count(customer_id))
            from comments
            where video_course_id = video_courses.id
            group by video_course_id),0) as raiting_sum_point,
       ceil(IFNULL((select (count(id) / (select count(id) from videos where is_deleted=0 and videos.subject_id in (select id from subjects where is_deleted=0 and status=1 and subjects.video_course_id = video_courses.id)))
            from video_done
            where video_course_id = video_courses.id and customer_id=?
            group by video_course_id),0) * 100)  as done_decimal,
            IFNULL((select count(id)
            from comments
            where video_course_id = video_courses.id
            group by video_course_id),0) as raiting_count,
            50 AS video_count,
            10 as subjects_count
       ', [$request->user()->id])->where('type',$type)
         ->where('is_deleted',0)->where('status',1)->with(['comments.customer','subjects.videos'])
             ->whereHas('groupsFilter', function ($q) use ($groupAdded) {
                 $q->where(function ($subQuery) use ($groupAdded) {
                     foreach ($groupAdded as $index => $group) {
                         $method = $index === 0 ? 'where' : 'orWhere';
                         $subQuery->$method(function ($query) use ($group) {
                             $query->where('group_id', $group['group_id']);
                             if (!empty($group['end_date'])) {
                                 $query->where('created_at', '<=', $group['end_date']);
                             }
                             if (!empty($group['date'])) {
                                 $query->where('created_at', '>=', $group['date']);
                             }
                         });
                     }
                 });
             });


          if($searchKey!=null){
              $videoCourses = $videoCourses
                                ->where('name',"like", "%".$searchKey."%");
          }

          if(isset($groupParams)){
              $videoCourses = $videoCourses->where(function ($query) use ($groupParams) {
                        foreach ($groupParams as $groupParam) {
                            $query->orWhere('group_ids', 'like', '%"'.$groupParam.'"%');
                        }
                    });
          }

          if($orderBy!=null){
              $orderBy = explode("_",$orderBy);
              $videoCourses = $videoCourses->orderBy($orderBy[0],$orderBy[1]);
          }

          if($paginate!=null){
              $videoCourses = $videoCourses->paginate($paginate);
          }else{
              $videoCourses = $videoCourses->get();
          }

         return response(['status' => 'success', 'videoCourses' => $videoCourses]);
    }

    public function setVideoDone(Request $request){
        $videoId = $request->videoId;
        $videoCourseId = $request->videoCourseId;

        $result = VideoDone::create([
                'video_id'=>$videoId,
                'video_course_id'=>$videoCourseId,
                'customer_id'=>$request->user()->id
            ]);

        return response(['status' => 'success', 'result' => $result]);
    }


    public function exam(Request $request){
         $paginate = $request->limit ?? null;
         $orderBy = $request->orderBy ?? null;
         $endDate = @$request->user()->end_date;
         $searchKey = $_GET['searchKey'] ?? null;
         $groups = json_decode($request->user()->group_ids);
         $groupParams = @$request->groups;
         $list = Exam::with('questions')->withCount('questions')->where('status',1)->where("is_deleted",0);

         if($endDate!=null){
              $list = $list->where('created_at',"<=", $endDate);
          }

         $list = $list->where(function ($query) use ($groups) {
            foreach ($groups as $group) {
                $query->orWhere('group_ids', 'like', '%"'.$group.'"%');
            }
        });

        if(isset($groupParams)){
              $list = $list->where(function ($query) use ($groupParams) {
                    foreach ($groupParams as $groupParam) {
                        $query->orWhere('group_ids', 'like', '%"'.$groupParam.'"%');
                    }
                });
          }

          if($searchKey!=null){
              $list = $list->where('name',"like", "%".$searchKey."%");
          }

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

}
