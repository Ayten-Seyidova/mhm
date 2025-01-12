<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Register;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $posts = Register::where(function ($query) use ($request) {
            return $request->search ?
                $query->from('search')->where('username', 'like', "%$request->search%")->orWhere('email', 'like', "%$request->search%")->orWhere('class', 'like', "%$request->search%")->orWhere('lesson', 'like', "%$request->search%") : '';
        })->orderBy('id', 'desc')->paginate(20);

        return view('admin.pages.register', compact('posts'));
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
        Register::find($id)->delete();
        return response()->json(['message' => 'Uğurlu']);
    }

    public function checked(Request $request)
    {
        $arr = $request->arr;

        foreach ($arr as $id) {
            Register::find($id)->delete();
        }

        return response()->json(['message' => 'Uğurlu']);
    }
}
