<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\OurTeacherRequest;
use App\Models\OurTeacher;
use Illuminate\Http\Request;

class OurTeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $posts = OurTeacher::where(function ($query) use ($request) {
            return $request->search ?
                $query->from('search')->where('name', 'like', "%$request->search%")->orWhere('link', 'like', "%$request->search%")->orWhere('subject', 'like', "%$request->search%")->orWhere('description', 'like', "%$request->search%") : '';
        })->where(function ($query) use ($request) {
            return $request->status ?
                $query->from('status')->where('status', $request->status) : '';
        })->orderBy('id', 'desc')->paginate(20);

        return view('admin.pages.our-teacher', compact('posts'));
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
    public function store(OurTeacherRequest $request)
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

        OurTeacher::create([
            'image' => $image ? uploadImg($image) : 'postImage/noUser.png',
            'name' => $request->name,
            'subject' => $request->subject,
            'description' => str_replace(['<iframe', '&#39;'], ['<iframe allowfullscreen', "'"], $request->description),
            'link' => $url,
            'status' => isset($request->status) ? 1 : 0,
        ]);

        alert()->success('Uğurlu', 'Əlavə olundu')
            ->showConfirmButton('Tamam', '#163A76');

        return redirect()->route('our-teacher.index');
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
        $post = OurTeacher::find($id);
        return response()->json(['post' => $post], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OurTeacherRequest $request, string $id)
    {
        $postUpdate = OurTeacher::find($id);

        $image = $request->file('image');

        if ($_POST['hidden'] == "0") {
            $postUpdate->image = 'postImage/noUser.png';
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
            }
        }

        $postUpdate->link = $url;
        $postUpdate->name = $request->name;
        $postUpdate->subject = $request->subject;
        $postUpdate->description = str_replace(['<iframe', '&#39;'], ['<iframe allowfullscreen', "'"], $request->description);
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
        OurTeacher::find($id)->delete();
        return response()->json(['message' => 'Uğurlu']);
    }

    public function changeStatus(Request $request)
    {
        try {
            $postID = $request->id;
            $post = OurTeacher::find($postID);
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
                $post = OurTeacher::find($id);
                $post->status = 0;
                $post->save();
            }
        } else if ($request->val == 1) {
            foreach ($arr as $id) {
                $post = OurTeacher::find($id);
                $post->status = 1;
                $post->save();
            }
        } else if ($request->val == 2) {
            foreach ($arr as $id) {
                OurTeacher::find($id)->delete();
            }
        }

        return response()->json(['message' => 'Uğurlu']);
    }
}
