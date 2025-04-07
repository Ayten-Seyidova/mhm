<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubDirectionRequest;
use App\Models\Direction;
use App\Models\SubDirection;
use Illuminate\Http\Request;

class SubDirectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $directions = Direction::where('is_deleted', 0)->get();
        $isDeleted = $request->is_deleted;
        if (!isset($isDeleted)) {
            $isDeleted = 0;
        }
        $posts = SubDirection::where('is_deleted', $isDeleted)->where(function ($query) use ($request) {
            return $request->search ?
                $query->from('search')->where('title', 'like', "%$request->search%") : '';
        })->where(function ($query) use ($request) {
            return $request->direction_id ?
                $query->from('direction_id')->where('direction_id', $request->direction_id) : '';
        })->orderBy('id', 'desc')->paginate(20);

        return view('admin.pages.sub-direction', compact('posts', 'directions'));
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
    public function store(SubDirectionRequest $request)
    {
        SubDirection::create([
            'direction_id' => $request->direction_id,
            'title' => $request->title,
        ]);


        alert()->success('Uğurlu', 'Əlavə olundu')
            ->showConfirmButton('Tamam', '#163A76');

        return redirect()->route('sub-direction.index');
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
        $post = SubDirection::find($id);
        return response()->json(['post' => $post], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SubDirectionRequest $request, string $id)
    {
        $postUpdate = SubDirection::find($id);

        $postUpdate->title = $request->title;
        $postUpdate->direction_id = $request->direction_id;

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
        $customer = SubDirection::find($id);
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
            $customer = SubDirection::find($id);
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
