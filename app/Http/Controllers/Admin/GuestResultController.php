<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guest;
use App\Models\GuestExam;
use App\Models\GuestResult;
use App\Models\TeacherSubDirection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuestResultController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = null;

        $exams = GuestExam::where('status', 1)->where('is_deleted', 0)->get();
        $guests = Guest::where('is_deleted', 0)->get();

        if (Auth::guard('teacher')->check()) {
            $user = Auth::guard('teacher')->user();
            $exams = GuestExam::where('status', 1)
                ->where('is_deleted', 0)
                ->where('user_id', $user->id)
                ->get();

            $teaherDirectionIds = TeacherSubDirection::where('user_id', $user->id)->pluck('sub_direction_id');
            $guests = Guest::where('is_deleted', 0)->whereIn('id', $teaherDirectionIds)->get();
        }

        $posts = GuestResult::where(function ($query) use ($request) {
            if ($request->search) {
                $query->where('correct_count', 'like', "%{$request->search}%");
            }
        })
            ->where(function ($query) use ($request) {
                if ($request->guest_exam_id) {
                    $query->where('guest_exam_id', $request->guest_exam_id);
                }
            })
            ->where(function ($query) use ($request) {
                if ($request->guest_id) {
                    $query->where('guest_id', $request->guest_id);
                }
            })
            ->orderBy('id', 'desc')
            ->paginate(20);

        return view('admin.pages.guest-result', compact('posts', 'exams', 'guests'));
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
