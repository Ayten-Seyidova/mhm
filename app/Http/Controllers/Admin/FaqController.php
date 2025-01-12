<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FaqRequest;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $posts = Faq::where(function ($query) use ($request) {
            return $request->search ?
                $query->from('search')->where('title', 'like', "%$request->search%")->orWhere('content', 'like', "%$request->search%") : '';
        })->orderBy('id', 'desc')->paginate(20);

        return view('admin.pages.faq', compact('posts'));
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
    public function store(FaqRequest $request)
    {
        Faq::create([
            'title' => $request->title,
            'content' => str_replace(['<iframe', '&#39;'], ['<iframe allowfullscreen', "'"], $request->content),
        ]);

        alert()->success('Uğurlu', 'Əlavə olundu')
            ->showConfirmButton('Tamam', '#163A76');

        return redirect()->route('faq.index');
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
        $post = Faq::find($id);
        return response()->json(['post' => $post], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FaqRequest $request, string $id)
    {
        $settings = Faq::find($id);

        $settings->title = $request->title;
        $settings->content = str_replace(['<iframe', '&#39;'], ['<iframe allowfullscreen', "'"], $request->content);
        $settings->save();

        alert()->success('Uğurlu', 'Redaktə olundu')
            ->showConfirmButton('Tamam', '#3085d6');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Faq::find($id)->delete();
        return response()->json(['message' => 'Uğurlu']);
    }

    public function checked(Request $request)
    {
        $arr = $request->arr;

        foreach ($arr as $id) {
            Faq::find($id)->delete();
        }

        return response()->json(['message' => 'Uğurlu']);
    }
}
