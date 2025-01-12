<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\VideoCourseRequest;
use App\Models\Group;
use App\Models\VideoCourse;
use App\Models\CourseGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VideoCourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = null;
        $groups = Group::where('is_deleted', 0);

        if (Auth::guard('teacher')->check()) {
            $user = Auth::guard('teacher')->user();
            $groups = $groups->where('user_id', $user->id);
        }

        $groups = $groups->get();

        $isDeleted = $request->is_deleted ? $request->is_deleted : 0;

        $posts = VideoCourse::where('is_deleted', $isDeleted)
            ->where(function ($query) use ($request) {
                if ($request->search) {
                    $query->where('name', 'like', "%{$request->search}%")
                        ->orWhere('description', 'like', "%{$request->search}%");
                }
            })
            ->where(function ($query) use ($request) {
                if ($request->status) {
                    $query->where('status', $request->status);
                }
            })
            ->where(function ($query) use ($request, $user) {
                if ($request->group_id) {
                    $categoryId = $request->group_id;
                    $query->whereRaw("JSON_CONTAINS(group_ids, '\"$categoryId\"')");
                }
            })
            ->where(function ($query) use ($request) {
                if ($request->type) {
                    $query->where('type', $request->type);
                }
            })
            ->when(Auth::guard('teacher')->check(), function ($query) use ($user) {
                $groupIds = Group::where('user_id', $user->id)
                    ->where('is_deleted', 0)
                    ->pluck('id')
                    ->toArray();

                $query->where(function ($query) use ($groupIds) {
                    foreach ($groupIds as $groupId) {
                        $query->orWhereRaw("JSON_CONTAINS(group_ids, '\"$groupId\"')");
                    }
                });
            })
            ->orderBy('status', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(20);

        return view('admin.pages.video-course', compact('posts', 'groups'));

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
    public function store(VideoCourseRequest $request)
    {
        $image = $request->file('image');
        VideoCourse::create([
            'image' => $image ? uploadImg($image) : 'postImage/noPhoto.png',
            'name' => $request->name,
            'type' => $request->type,
            'status' => isset($request->status) ? 1 : 0,
            'duration' => $request->duration,
            'group_ids' => $request->group_ids ? json_encode($request->group_ids) : '',
            'description' => $request->description,
        ]);
        
        $last = VideoCourse::orderBy('id', 'desc')->first();
            if(!empty($last)){
        foreach($request->group_ids as $groupId) {
                    CourseGroup::create([
              'video_course_id' => $last->id,
            'group_id' => $groupId,
        ]);
        }
        }

        alert()->success('Uğurlu', 'Əlavə olundu')
            ->showConfirmButton('Tamam', '#163A76');

        return redirect()->route('video-course.index');
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
        $post = VideoCourse::find($id);
        return response()->json(['post' => $post], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(VideoCourseRequest $request, string $id)
    {
        $postUpdate = VideoCourse::find($id);

        $image = $request->file('image');

        if ($_POST['hidden'] == "0") {
            $postUpdate->image = 'postImage/noPhoto.png';
        } else if ($image && $_POST['hidden'] == "1") {
            $postUpdate->image = uploadImg($image);
        }

        $postUpdate->name = $request->name;
        $postUpdate->description = $request->description;
        $postUpdate->duration = $request->duration;
        $postUpdate->type = $request->type;
        $postUpdate->status = isset($request->status) ? 1 : 0;
        $postUpdate->group_ids = json_encode($request->group_ids);

        $postUpdate->save();
        
        foreach($request->group_ids as $groupId) {
            $courseGroup = CourseGroup::where('group_id', $groupId)->where('video_course_id', $id)->first();
            if(empty($courseGroup)) {
                              CourseGroup::create([
              'video_course_id' => $id,
            'group_id' => $groupId,
        ]);
            }
        }

        alert()->success('Uğurlu', 'Redaktə olundu')
            ->showConfirmButton('Tamam', '#163A76');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $customer = VideoCourse::find($id);
        if ($customer->is_deleted == 0) {
            $customer->is_deleted = 1;
        } else {
            $customer->is_deleted = 0;
        }
        $customer->save();
        return response()->json(['message' => 'Uğurlu']);
    }

    public function changeStatus(Request $request)
    {
        try {
            $postID = $request->id;
            $post = VideoCourse::find($postID);
            $status = $post->status;
            $post->status = $status ? 0 : 1;

            $post->save();

            return response()->json(['message' => 'Uğurlu', 'status' => $post->status], 200);
        } catch (\Exception $exception) {
            return response()->json(['message' => 'Xəta', 'status' => $post->status], 500);
        }
    }

    public function checked(Request $request)
    {
        $arr = $request->arr;

        if ($request->val == 0) {
            foreach ($arr as $id) {
                $post = VideoCourse::find($id);
                $post->status = 0;
                $post->save();
            }
        } else if ($request->val == 1) {
            foreach ($arr as $id) {
                $post = VideoCourse::find($id);
                $post->status = 1;
                $post->save();
            }
        } else {
            foreach ($arr as $id) {
                $customer = VideoCourse::find($id);
                if ($customer->is_deleted == 0) {
                    $customer->is_deleted = 1;
                } else {
                    $customer->is_deleted = 0;
                }
                $customer->save();
            }
        }

        return response()->json(['message' => 'Uğurlu']);
    }
}
