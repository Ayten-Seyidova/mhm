<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Video;
use App\Models\VideoCourse;
use App\Models\Exam;
use App\Models\Api\VideoDone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class VideoController extends Controller
{
    public function videoCourses($type, Request $request)
    {
        $paginate   = $request->get('limit');
        $orderBy    = $request->get('orderBy');
        $searchKey  = $request->get('searchKey');
        $groupParams = $request->groups;
        $user       = $request->user();

        $groupAdded = Cache::remember("user_groups_{$user->id}", now()->addMinutes(30), function () use ($user) {
            return $user->load("groupAdded")->groupAdded;
        });

        $cacheKey = "video_courses_{$type}_" . md5(json_encode([
                'limit'       => $paginate,
                'orderBy'     => $orderBy,
                'searchKey'   => $searchKey,
                'groups'      => $groupParams,
                'userGroups'  => $groupAdded->pluck('group_id'),
            ]));

        $videoCourses = Cache::remember($cacheKey, now()->addMinutes(30), function () use ($type, $paginate, $orderBy, $searchKey, $groupParams, $groupAdded) {

            // Sadəcə statistikaları bir dəfə yığ (əlavə join yerinə)
            $stats = DB::table('video_courses')
                ->leftJoin('comments', 'video_courses.id', '=', 'comments.video_course_id')
                ->leftJoin('subjects', 'video_courses.id', '=', 'subjects.video_course_id')
                ->leftJoin('videos', 'videos.subject_id', '=', 'subjects.id')
                ->where('video_courses.type', $type)
                ->where('video_courses.is_deleted', 0)
                ->where('video_courses.status', 1)
                ->where('subjects.is_deleted', 0)
                ->where('subjects.status', 1)
                ->groupBy('video_courses.id')
                ->selectRaw('
                video_courses.id,
                COUNT(DISTINCT subjects.id) as subjects_count,
                COUNT(DISTINCT videos.id) as video_count,
                COUNT(comments.id) as raiting_count,
                IFNULL(AVG(comments.rate), 0) as raiting_sum_point
            ')
                ->get()
                ->keyBy('id');

            $query = VideoCourse::where('type', $type)
                ->where('is_deleted', 0)
                ->where('status', 1)
                ->with(['groupsFilter:id,video_course_id,group_id', 'groups:id,name', 'subjects:id,video_course_id', 'comments:id,video_course_id,rate']);

            if ($groupAdded->isNotEmpty()) {
                $query->whereHas('groupsFilter', function ($q) use ($groupAdded) {
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
            }

            if ($searchKey) {
                $query->where('name', 'like', "%$searchKey%");
            }

            if (!empty($groupParams)) {
                $query->whereHas('groups', function ($q) use ($groupParams) {
                    $q->whereIn('group_id', $groupParams);
                });
            }

            if ($orderBy) {
                $parts = explode("_", $orderBy);
                $query->orderBy($parts[0], $parts[1] ?? 'asc');
            }

            $videoCourses = $paginate ? $query->paginate($paginate)->toArray()['data'] : $query->get()->toArray();

            foreach ($videoCourses as $key => $vc) {
                if (isset($stats[$vc['id']])) {
                    $videoCourses[$key]['subjects_count']     = $stats[$vc['id']]->subjects_count;
                    $videoCourses[$key]['video_count']        = $stats[$vc['id']]->video_count;
                    $videoCourses[$key]['raiting_count']      = $stats[$vc['id']]->raiting_count;
                    $videoCourses[$key]['raiting_sum_point']  = $stats[$vc['id']]->raiting_sum_point;
                } else {
                    $videoCourses[$key]['subjects_count']     = 0;
                    $videoCourses[$key]['video_count']        = 0;
                    $videoCourses[$key]['raiting_count']      = 0;
                    $videoCourses[$key]['raiting_sum_point']  = 0;
                }
            }

            return $videoCourses;
        });

        return response([
            'status'       => 'success',
            'videoCourses' => $videoCourses
        ]);
    }


    public function myVideoCourses($type, Request $request)
    {
        $paginate   = $request->get('limit');
        $orderBy    = $request->get('orderBy');
        $searchKey  = $request->get('searchKey');
        $groupParams = $request->groups;
        $user       = $request->user();

        $groupAdded = Cache::remember("user_groups_{$user->id}", now()->addMinutes(30), function () use ($user) {
            return $user->load("groupAdded")->groupAdded;
        });

        $cacheKey = "video_courses_{$type}_" . md5(json_encode([
                'limit'       => $paginate,
                'orderBy'     => $orderBy,
                'searchKey'   => $searchKey,
                'groups'      => $groupParams,
                'userGroups'  => $groupAdded->pluck('group_id'),
            ]));

        $videoCourses = Cache::remember($cacheKey, now()->addMinutes(30), function () use ($type, $paginate, $orderBy, $searchKey, $groupParams, $groupAdded) {

            $stats = DB::table('video_courses')
                ->leftJoin('comments', 'video_courses.id', '=', 'comments.video_course_id')
                ->leftJoin('subjects', 'video_courses.id', '=', 'subjects.video_course_id')
                ->leftJoin('videos', 'videos.subject_id', '=', 'subjects.id')
                ->where('video_courses.type', $type)
                ->where('video_courses.is_deleted', 0)
                ->where('video_courses.status', 1)
                ->where('subjects.is_deleted', 0)
                ->where('subjects.status', 1)
                ->groupBy('video_courses.id')
                ->selectRaw('
                video_courses.id,
                COUNT(DISTINCT subjects.id) as subjects_count,
                COUNT(DISTINCT videos.id) as video_count,
                COUNT(comments.id) as raiting_count,
                IFNULL(AVG(comments.rate), 0) as raiting_sum_point
            ')
                ->get()
                ->keyBy('id');

            $query = VideoCourse::where('type', $type)
                ->where('is_deleted', 0)
                ->where('status', 1)
                ->with(['groupsFilter:id,video_course_id,group_id', 'groups:id,name', 'subjects:id,video_course_id', 'comments:id,video_course_id,rate']);

            if ($groupAdded->isNotEmpty()) {
                $query->whereHas('groupsFilter', function ($q) use ($groupAdded) {
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
            }

            if ($searchKey) {
                $query->where('name', 'like', "%$searchKey%");
            }

            if (!empty($groupParams)) {
                $query->whereHas('groups', function ($q) use ($groupParams) {
                    $q->whereIn('group_id', $groupParams);
                });
            }

            if ($orderBy) {
                $parts = explode("_", $orderBy);
                $query->orderBy($parts[0], $parts[1] ?? 'asc');
            }

            $videoCourses = $paginate ? $query->paginate($paginate)->toArray()['data'] : $query->get()->toArray();

            foreach ($videoCourses as $key => $vc) {
                if (isset($stats[$vc['id']])) {
                    $videoCourses[$key]['subjects_count']     = $stats[$vc['id']]->subjects_count;
                    $videoCourses[$key]['video_count']        = $stats[$vc['id']]->video_count;
                    $videoCourses[$key]['raiting_count']      = $stats[$vc['id']]->raiting_count;
                    $videoCourses[$key]['raiting_sum_point']  = $stats[$vc['id']]->raiting_sum_point;
                } else {
                    $videoCourses[$key]['subjects_count']     = 0;
                    $videoCourses[$key]['video_count']        = 0;
                    $videoCourses[$key]['raiting_count']      = 0;
                    $videoCourses[$key]['raiting_sum_point']  = 0;
                }
            }

            return $videoCourses;
        });

        return response([
            'status'       => 'success',
            'videoCourses' => $videoCourses
        ]);
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
         //$date = @$request->user()->date;
         $endDate = @$request->user()->end_date;
         $searchKey = $_GET['searchKey'] ?? null;
         $groups = json_decode($request->user()->group_ids);
         $groupParams = @$request->groups;
         $list = Exam::with('questions')->withCount('questions')->where('status',1)->where("is_deleted",0);


         /*if($date!=null){
              $list = $list->where('created_at',">=", $date);
          }*/


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

}
