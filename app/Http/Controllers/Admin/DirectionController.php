<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DirectionRequest;
use App\Models\Direction;
use Illuminate\Http\Request;

class DirectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $isDeleted = $request->is_deleted;
        if (!isset($isDeleted)) {
            $isDeleted = 0;
        }
        $posts = Direction::where('is_deleted', $isDeleted)->where(function ($query) use ($request) {
            return $request->search ?
                $query->from('search')->where('title', 'like', "%$request->search%") : '';
        })->orderBy('id', 'desc')->paginate(20);
        return view('admin.pages.direction', compact('posts'));
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
    public function store(DirectionRequest $request)
    {
        Direction::create([
            'title' => $request->title,
        ]);

        alert()->success('Uğurlu', 'Əlavə olundu')
            ->showConfirmButton('Tamam', '#163A76');

        return redirect()->route('direction.index');
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
        $post = Direction::find($id);
        return response()->json(['post' => $post], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DirectionRequest $request, string $id)
    {
        $postUpdate = Direction::find($id);

        $postUpdate->title = $request->title;

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
        $customer = Direction::find($id);
        if ($customer->is_deleted == 0) {
            $customer->is_deleted = 1;
        } else {
            $customer->is_deleted = 0;
        }

        $customer->save();
        return response()->json(['message' => 'Uğurlu']);
    }

    public function checked(Request $request)
    {
        $arr = $request->arr;

        foreach ($arr as $id) {
            $customer = Direction::find($id);
            if ($customer->is_deleted == 0) {
                $customer->is_deleted = 1;
            } else {
                $customer->is_deleted = 0;
            }
            $customer->save();
        }

        return response()->json(['message' => 'Uğurlu']);
    }
}
