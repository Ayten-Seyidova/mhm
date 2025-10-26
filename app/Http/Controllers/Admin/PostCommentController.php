<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guest;
use App\Models\GuestComment;
use App\Models\Post;
use Illuminate\Http\Request;

class PostCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $postId = $request->post_id;
        if (!empty($postId)) {
            $post = Post::find($postId);
            if (!empty($post)) {
                $guests = Guest::where('is_deleted', 0)->where('status', 1)->get();
                $posts = GuestComment::with('post')->with('guest')->where('post_id', $postId)->where(function ($query) use ($request) {
                    return $request->search ?
                        $query->from('search')->where('comment', 'like', "%$request->search%") : '';
                })->where(function ($query) use ($request) {
                    return $request->guest_id ?
                        $query->from('guest_id')->where('guest_id', $request->guest_id) : '';
                })->orderBy('id', 'desc')->paginate(20);

                return view('admin.pages.post-comment', compact('posts', 'guests'));
            } else {
                abort(404);
            }
        } else {
            abort(404);
        }
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
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $comment = GuestComment::find($id);
        $comment->delete();
        return response()->json(['message' => 'Uğurlu']);
    }
}
