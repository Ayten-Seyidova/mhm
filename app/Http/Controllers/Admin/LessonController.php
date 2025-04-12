<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LessonRequest;
use App\Models\Direction;
use App\Models\Lesson;
use App\Models\SubDirection;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $directions = Direction::where('is_deleted', 0)->get();
        $allSubDirections = SubDirection::where('is_deleted', 0)->get();

        if ($request->direction_id) {
            $subDirections = SubDirection::where('direction_id', $request->direction_id)->where('is_deleted', 0)->get();
        } else {
            $subDirections = $allSubDirections;
        }

        $posts = Lesson::with('subDirection')
            ->where(function ($query) use ($request) {
                if ($request->search) {
                    $query->where('content', 'like', "%{$request->search}%")->orWhere('title', 'like', "%{$request->search}%");
                }
            })
            ->where(function ($query) use ($request) {
                return $request->sub_direction_id ?
                    $query->from('sub_direction_id')->where('sub_direction_id', $request->sub_direction_id) : '';
            })
            ->when($request->direction_id, function ($query) use ($request) {
                return $query->whereHas('subDirection', function ($q) use ($request) {
                    $q->where('direction_id', $request->direction_id);
                });
            })->where(function ($query) use ($request) {
                return $request->status ?
                    $query->from('status')->where('status', $request->status) : '';
            });

        $posts = $posts->orderBy('id', 'desc')->paginate(20);
        return view('admin.pages.lesson', compact('posts', 'directions', 'subDirections', 'allSubDirections'));
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
    public function store(LessonRequest $request)
    {
        $image = $request->file('image');

        $url = $request->video;
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

        Lesson::create([
            'image' => $image ? uploadImg($image) : 'postImage/noPhoto.png',
            'title' => $request->title,
            'content' => $request->content,
            'video' => $url,
            'status' => isset($request->status) ? 1 : 0,
            'sub_direction_id' => $request->sub_direction_id,
        ]);

        alert()->success('Uğurlu', 'Əlavə olundu')
            ->showConfirmButton('Tamam', '#163A76');

        return redirect()->route('lesson.index');
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
        $post = Lesson::with('subDirection')->find($id);
        return response()->json(['post' => $post], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LessonRequest $request, string $id)
    {
        $postUpdate = Lesson::find($id);

        $image = $request->file('image');

        if ($_POST['hidden'] == "0") {
            $postUpdate->image = 'postImage/noPhoto.png';
        } else if ($image && $_POST['hidden'] == "1") {
            $postUpdate->image = uploadImg($image);
        }

        $url = $request->video;
        $position = strpos($url, '=');

        if ($position !== false) {
            $parsedUrl = parse_url($url);

            parse_str($parsedUrl['query'] ?? '', $queryParams);

            $videoId = $queryParams['v'] ?? null;

            if ($videoId) {
                $url = $videoId;
            }
        }

        $postUpdate->video = $url;
        $postUpdate->content = $request->content;
        $postUpdate->title = $request->title;
        $postUpdate->sub_direction_id = $request->sub_direction_id;
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
        Lesson::find($id)->delete();
        return response()->json(['message' => 'Uğurlu']);
    }

    public function changeStatus(Request $request)
    {
        try {
            $postID = $request->id;
            $post = Lesson::find($postID);
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
                $post = Lesson::find($id);
                $post->status = 0;
                $post->save();
            }
        } else if ($request->val == 1) {
            foreach ($arr as $id) {
                $post = Lesson::find($id);
                $post->status = 1;
                $post->save();
            }
        } else if ($request->val == 2) {
            foreach ($arr as $id) {
                Lesson::find($id)->delete();
            }
        }

        return response()->json(['message' => 'Uğurlu']);
    }
}
