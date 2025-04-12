<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AboutRequest;
use App\Models\About;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setting = About::find(1);

        return view('admin.pages.about', compact('setting'));
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
    public function update(AboutRequest $request, string $id)
    {
        $settings = About::find($id);

        $image = $request->file('image');

        if ($_POST['hidden'] == "0") {
            $settings->image = 'postImage/noUser.png';
        } else if ($image && $_POST['hidden'] == "1") {
            $settings->image = uploadImg($image);
        }

        $settings->about = str_replace(['<iframe', '&#39;'], ['<iframe allowfullscreen', "'"], $request->about);
        $settings->save();

        alert()->success('Uğurlu', 'Redaktə olundu')
            ->showConfirmButton('Tamam', '#3085d6');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
