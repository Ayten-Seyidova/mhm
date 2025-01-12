<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SliderRequest;
use App\Models\Slider;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $posts = Slider::where(function ($query) use ($request) {
            return $request->search ?
                $query->from('search')->where('title', 'like', "%$request->search%")->orWhere('link', 'like', "%$request->search%") : '';
        })->orderBy('id', 'desc')->paginate(20);

        return view('admin.pages.slider', compact('posts'));
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
    public function store(SliderRequest $request)
    {
        $image = $request->file('image');
        Slider::create([
            'image' => $image ? uploadImg($image) : 'postImage/noPhoto.png',
            'title' => $request->title,
            'link' => $request->link,
        ]);

        alert()->success('Uğurlu', 'Əlavə olundu')
            ->showConfirmButton('Tamam', '#163A76');

        return redirect()->route('slider.index');
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
        $post = Slider::find($id);
        return response()->json(['post' => $post], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SliderRequest $request, string $id)
    {
        $postUpdate = Slider::find($id);

        $image = $request->file('image');

        if ($_POST['hidden'] == "0") {
            $postUpdate->image = 'postImage/noPhoto.png';
        } else if ($image && $_POST['hidden'] == "1") {
            $postUpdate->image = uploadImg($image);
        }

        $postUpdate->title = $request->title;
        $postUpdate->link = $request->link;

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
        Slider::find($id)->delete();
        return response()->json(['message' => 'Uğurlu']);
    }

    public function checked(Request $request)
    {
        $arr = $request->arr;

        foreach ($arr as $id) {
            Slider::find($id)->delete();
        }

        return response()->json(['message' => 'Uğurlu']);
    }
}
