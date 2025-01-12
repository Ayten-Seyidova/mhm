<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubjectRequest;
use App\Models\Group;
use App\Models\Subject;
use App\Models\VideoCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = null;

        $courses = VideoCourse::where('status', 1)->where('is_deleted', 0)->get();

        if (Auth::guard('teacher')->check()) {
            $user = Auth::guard('teacher')->user();
            $courses = VideoCourse::where('status', 1)
                ->where('is_deleted', 0)
                ->where(function ($query) use ($user) {
                    $groupIds = Group::where('user_id', $user->id)
                        ->where('is_deleted', 0)
                        ->pluck('id')
                        ->toArray();

                    $query->where(function ($query) use ($groupIds) {
                        foreach ($groupIds as $groupId) {
                            $query->orWhereRaw("JSON_CONTAINS(group_ids, '\"$groupId\"')");
                        }
                    });
                })->get();
        }

        $isDeleted = $request->is_deleted ?? 0;

        $posts = Subject::where('is_deleted', $isDeleted)
            ->where(function ($query) use ($request) {
                if ($request->search) {
                    $query->where('name', 'like', "%{$request->search}%");
                }
            })
            ->where(function ($query) use ($request) {
                if ($request->video_course_id) {
                    $query->where('video_course_id', $request->video_course_id);
                }
            })
            ->when(Auth::guard('teacher')->check(), function ($query) use ($user) {
                $groupIds = Group::where('user_id', $user->id)
                    ->where('is_deleted', 0)
                    ->pluck('id')
                    ->toArray();

                $videoCourseIds = VideoCourse::where(function ($query) use ($groupIds) {
                    foreach ($groupIds as $groupId) {
                        $query->orWhereRaw("JSON_CONTAINS(group_ids, '\"$groupId\"')");
                    }
                })->pluck('id')->toArray();

                $query->whereIn('video_course_id', $videoCourseIds);
            })
            ->orderBy('status', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(20);

        return view('admin.pages.subject', compact('posts', 'courses'));
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
    public function store(SubjectRequest $request)
    {
        Subject::create([
            'name' => $request->name,
            'video_course_id' => $request->video_course_id,
            'status' => isset($request->status) ? 1 : 0,
        ]);

        alert()->success('Uğurlu', 'Əlavə olundu')
            ->showConfirmButton('Tamam', '#163A76');

        return redirect()->route('subject.index');
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
        $post = Subject::find($id);
        return response()->json(['post' => $post], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SubjectRequest $request, string $id)
    {
        $postUpdate = Subject::find($id);

        $postUpdate->name = $request->name;
        $postUpdate->video_course_id = $request->video_course_id;
        $postUpdate->status = isset($request->status) ? 1 : 0;

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
        $customer = Subject::find($id);
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
            $post = Subject::find($postID);
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
                $post = Subject::find($id);
                $post->status = 0;
                $post->save();
            }
        } else if ($request->val == 1) {
            foreach ($arr as $id) {
                $post = Subject::find($id);
                $post->status = 1;
                $post->save();
            }
        } else {
            foreach ($arr as $id) {
                $customer = Subject::find($id);
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
