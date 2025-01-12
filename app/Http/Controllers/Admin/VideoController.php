<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\VideoRequest;
use App\Models\Group;
use App\Models\Subject;
use App\Models\Video;
use App\Models\VideoCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = null;

        $courses = VideoCourse::where('status', 1)->where('is_deleted', 0)->get();
        $subjects = Subject::where('status', 1)->where('is_deleted', 0)->get();

        if (Auth::guard('teacher')->check()) {
            $user = Auth::guard('teacher')->user();

            $groupIds = Group::where('user_id', $user->id)
                ->where('is_deleted', 0)
                ->pluck('id')
                ->toArray();

            $videoCourseIds = VideoCourse::where(function ($query) use ($groupIds) {
                foreach ($groupIds as $groupId) {
                    $query->orWhereRaw("JSON_CONTAINS(group_ids, '\"$groupId\"')");
                }
            })->pluck('id')->toArray();

            $subjects = Subject::whereIn('video_course_id', $videoCourseIds)->get();
            $courses = VideoCourse::whereIn('id', $videoCourseIds)
                ->where('status', 1)
                ->where('is_deleted', 0)
                ->get();
        }

        $isDeleted = $request->is_deleted ?? 0;

        $posts = Video::where('is_deleted', $isDeleted)
            ->where(function ($query) use ($request) {
                if ($request->search) {
                    $query->where('name', 'like', "%{$request->search}%")
                        ->orWhere('link', 'like', "%{$request->search}%");
                }
            })
            ->where(function ($query) use ($request, $subjects) {
                if ($request->subject_id) {
                    $query->where('subject_id', $request->subject_id);
                } else {
                    $query->whereIn('subject_id', $subjects->pluck('id'));
                }
            })
            ->whereHas('subject', function ($query) use ($subjects) {
                $query->whereIn('video_course_id', $subjects->pluck('video_course_id'));
            })
            ->orderBy('id', 'desc')
            ->paginate(20);

        return view('admin.pages.video', compact('posts', 'courses', 'subjects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VideoRequest $request)
    {
        $image = $request->file('image');
        
        $url = $request->link;
               $position = strpos($url, '=');

        if ($position !== false) {
        $parsedUrl = parse_url($url);
        
        parse_str($parsedUrl['query'] ?? '', $queryParams);
        
        $videoId = $queryParams['v'] ?? null;
        
        if ($videoId) {
            $url = $videoId;
        } else {
            $url = '';
        }
        }

        Video::create([
            'image' => $image ? uploadImg($image) : 'postImage/noPhoto.png',
            'name' => $request->name,
            'link' => $url,
            'subject_id' => $request->subject_id,
        ]);

        alert()->success('Uğurlu', 'Əlavə olundu')
            ->showConfirmButton('Tamam', '#163A76');

        return redirect()->route('video.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $post = Video::with('subject')->find($id);
        return response()->json(['post' => $post], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(VideoRequest $request, string $id)
    {
        $postUpdate = Video::find($id);

        $image = $request->file('image');

        if ($_POST['hidden'] == "0") {
            $postUpdate->image = 'postImage/noPhoto.png';
        } else if ($image && $_POST['hidden'] == "1") {
            $postUpdate->image = uploadImg($image);
        }
        
        $url = $request->link;
        $position = strpos($url, '=');

        if ($position !== false) {
                    $parsedUrl = parse_url($url);
        
        parse_str($parsedUrl['query'] ?? '', $queryParams);
        
        $videoId = $queryParams['v'] ?? null;
        
        if ($videoId) {
            $url = $videoId;
        } else {
            $url = $url;
        }
        }


        $postUpdate->name = $request->name;
        $postUpdate->subject_id = $request->subject_id;
        $postUpdate->link = $url;

        $postUpdate->save();

        alert()->success('Uğurlu', 'Redaktə olundu')
            ->showConfirmButton('Tamam', '#163A76');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $customer = Video::find($id);
        if ($customer->is_deleted == 0) {
            $customer->is_deleted = 1;
        } else {
            $customer->is_deleted = 0;
        }
        $customer->save();
        return response()->json(['message' => 'Uğurlu']);
    }

    public function checked(Request $request)
    {
        $arr = $request->arr;

        foreach ($arr as $id) {
            $customer = Video::find($id);
            if ($customer->is_deleted == 0) {
                $customer->is_deleted = 1;
            } else {
                $customer->is_deleted = 0;
            }
            $customer->save();
        }

        return response()->json(['message' => 'Uğurlu']);
    }
}
