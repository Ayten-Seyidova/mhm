<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Customer;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = null;

        $lists = Post::where('type', 'question')->where('is_deleted', 0)->get();
        $customers = Customer::where('is_deleted', 0)->get();

        $posts = Answer::where(function ($query) use ($request) {
            return $request->customer_id ?
                $query->from('customer_id')->where('customer_id', $request->customer_id) : '';
             })
            ->where(function ($query) use ($request) {
                return $request->post_id ?
                    $query->from('post_id')->where('post_id', $request->post_id) : '';
            })->latest()->paginate(50);

        if (Auth::guard('teacher')->check()) {
            $user = Auth::guard('teacher')->user();

            $lists = Post::where('type', 'question')->where('user_id', $user->id)->where('is_deleted', 0)->get();
            $posts = Answer::where('post_id', $lists->pluck('id'))
                ->where(function ($query) use ($request) {
                    return $request->customer_id ?
                        $query->from('customer_id')->where('customer_id', $request->customer_id) : '';
                })
                ->where(function ($query) use ($request) {
                    return $request->post_id ?
                        $query->from('post_id')->where('post_id', $request->post_id) : '';
                })
                ->latest()->paginate(50);
        }

        return view('admin.pages.answer', compact('posts', 'customers', 'lists'));
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
    public function destroy(string $id)
    {
        //
    }
}
