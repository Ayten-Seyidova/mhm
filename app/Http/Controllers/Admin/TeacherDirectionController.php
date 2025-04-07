<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TeacherDirectionRequest;
use App\Models\Direction;
use App\Models\SubDirection;
use App\Models\TeacherSubDirection;
use App\Models\User;
use Illuminate\Http\Request;

class TeacherDirectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $teachers = User::where('type', 'teacher')->get();
        $directions = Direction::where('is_deleted', 0)->get();
        $allSubDirections = SubDirection::where('is_deleted', 0)->get();

        if ($request->direction_id) {
            $subDirections = SubDirection::where('direction_id', $request->direction_id)->where('is_deleted', 0)->get();
        } else {
            $subDirections = $allSubDirections;
        }

        $posts = TeacherSubDirection::where(function ($query) use ($request) {
            return $request->user_id ?
                $query->from('user_id')->where('user_id', $request->user_id) : '';
        })->where(function ($query) use ($request) {
            return $request->sub_direction_id ?
                $query->from('sub_direction_id')->where('sub_direction_id', $request->sub_direction_id) : '';
        })->when($request->direction_id, function ($query) use ($request) {
            return $query->whereHas('subDirection', function($q) use ($request) {
                $q->where('direction_id', $request->direction_id);
            });
        })->orderBy('id', 'desc')->paginate(50);

        return view('admin.pages.teacher-direction', compact('posts', 'directions', 'subDirections', 'allSubDirections', 'teachers'));
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
    public function store(TeacherDirectionRequest $request)
    {
        TeacherSubDirection::create([
            'user_id' => $request->user_id,
            'sub_direction_id' => $request->sub_direction_id,
        ]);

        alert()->success('Uğurlu', 'Əlavə olundu')
            ->showConfirmButton('Tamam', '#163A76');

        return redirect()->route('teacher-direction.index');
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
        $post = TeacherSubDirection::with('subDirection')->find($id);
        return response()->json(['post' => $post], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TeacherDirectionRequest $request, string $id)
    {
        $postUpdate = TeacherSubDirection::find($id);

        $postUpdate->user_id = $request->user_id;
        $postUpdate->sub_direction_id = $request->sub_direction_id;

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
        TeacherSubDirection::find($id)->delete();
        return response()->json(['message' => 'Uğurlu']);
    }

    public function checked(Request $request)
    {
        $arr = $request->arr;

        foreach ($arr as $id) {
            TeacherSubDirection::find($id)->delete();
        }

        return response()->json(['message' => 'Uğurlu']);
    }
}
