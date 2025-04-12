<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookRequest;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $posts = Book::where(function ($query) use ($request) {
            return $request->search ?
                $query->from('search')->where('teacher_name', 'like', "%$request->search%")->orWhere('title', 'like', "%$request->search%")->orWhere('description', 'like', "%$request->search%") : '';
        })->where(function ($query) use ($request) {
            return $request->status ?
                $query->from('status')->where('status', $request->status) : '';
        })->orderBy('id', 'desc')->paginate(20);

        return view('admin.pages.book', compact('posts'));
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
    public function store(BookRequest $request)
    {
        $image = $request->file('image');

        Book::create([
            'image' => $image ? uploadImg($image) : 'postImage/noUser.png',
            'title' => $request->title,
            'teacher_name' => $request->teacher_name,
            'price' => $request->price,
            'description' => str_replace(['<iframe', '&#39;'], ['<iframe allowfullscreen', "'"], $request->description),
            'status' => isset($request->status) ? 1 : 0,
        ]);

        alert()->success('Uğurlu', 'Əlavə olundu')
            ->showConfirmButton('Tamam', '#163A76');

        return redirect()->route('book.index');
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
        $post = Book::find($id);
        return response()->json(['post' => $post], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BookRequest $request, string $id)
    {
        $postUpdate = Book::find($id);

        $image = $request->file('image');

        if ($_POST['hidden'] == "0") {
            $postUpdate->image = 'postImage/noUser.png';
        } else if ($image && $_POST['hidden'] == "1") {
            $postUpdate->image = uploadImg($image);
        }

        $postUpdate->teacher_name = $request->teacher_name;
        $postUpdate->title = $request->title;
        $postUpdate->price = $request->price;
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
        Book::find($id)->delete();
        return response()->json(['message' => 'Uğurlu']);
    }

    public function changeStatus(Request $request)
    {
        try {
            $postID = $request->id;
            $post = Book::find($postID);
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
                $post = Book::find($id);
                $post->status = 0;
                $post->save();
            }
        } else if ($request->val == 1) {
            foreach ($arr as $id) {
                $post = Book::find($id);
                $post->status = 1;
                $post->save();
            }
        } else if ($request->val == 2) {
            foreach ($arr as $id) {
                Book::find($id)->delete();
            }
        }

        return response()->json(['message' => 'Uğurlu']);
    }
}
