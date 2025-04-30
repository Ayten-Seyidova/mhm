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
        $allSubDirections = SubDirection::where('is_deleted', 0)->get();

        $subDirections = $allSubDirections;

        $posts = User::where('type', 'teacher')->where(function ($query) use ($request) {
            return $request->user_id ?
                $query->from('user_id')->where('id', $request->user_id) : '';
        })->when($request->sub_direction_id, function ($query) use ($request) {
            return $query->whereHas('subDirection', function ($q) use ($request) {
                $q->where('sub_direction_id', $request->sub_direction_id);
            });
        })->orderBy('id', 'desc')->get();

        return view('admin.pages.teacher-direction', compact('posts', 'subDirections', 'allSubDirections', 'teachers'));
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
        $directions = $request->sub_direction_id;

        if (!empty($directions[0])) {
            foreach ($directions as $direction) {
                $teacherSubDirection = TeacherSubDirection::where('user_id', $request->user_id)->where('sub_direction_id', $direction)->first();
                if (empty($teacherSubDirection)) {
                    TeacherSubDirection::create([
                        'user_id' => $request->user_id,
                        'sub_direction_id' => $direction,
                    ]);
                }
            }
        }

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
        $posts = TeacherSubDirection::where('user_id', $id)->get();
        return response()->json(['posts' => $posts], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TeacherDirectionRequest $request, string $id)
    {
        TeacherSubDirection::where('user_id', $id)->delete();
        $directions = $request->sub_direction_id;

        if (!empty($directions[0])) {
            foreach ($directions as $direction) {
                $teacherSubDirection = TeacherSubDirection::where('user_id', $request->user_id)->where('sub_direction_id', $direction)->first();
                if (empty($teacherSubDirection)) {
                    TeacherSubDirection::create([
                        'user_id' => $request->user_id,
                        'sub_direction_id' => $direction,
                    ]);
                }
            }
        }

        alert()->success('Uğurlu', 'Redaktə olundu')
            ->showConfirmButton('Tamam', '#163A76');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        TeacherSubDirection::where('user_id', $id)->delete();
        return response()->json(['message' => 'Uğurlu']);
    }

    public function checked(Request $request)
    {
        $arr = $request->arr;

        foreach ($arr as $id) {
            TeacherSubDirection::where('user_id', $id)->delete();
        }

        return response()->json(['message' => 'Uğurlu']);
    }
}
