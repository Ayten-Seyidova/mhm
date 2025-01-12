<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Exam;
use App\Models\Group;
use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResultController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = null;

        $exams = Exam::where('status', 1)->where('is_deleted', 0)->get();

        if (Auth::guard('teacher')->check()) {
            $user = Auth::guard('teacher')->user();
            $exams = Exam::where('status', 1)
                ->where('is_deleted', 0)
                ->where(function ($query) use ($user) {
                    $groupIds = Group::where('user_id', $user->id)
                        ->where('is_deleted', 0)
                        ->pluck('id')
                        ->toArray();

                    $query->where(function ($query) use ($groupIds) {
                        foreach ($groupIds as $groupId) {
                            $query->orWhereRaw("JSON_CONTAINS(group_ids, '\"$groupId\"')");
                        }
                    });
                })->get();
        }

        $customers = Customer::where('is_deleted', 0)->get();

        if (Auth::guard('teacher')->check()) {
            $user = Auth::guard('teacher')->user();
            $customers = Customer::where('is_deleted', 0)
                ->where(function ($query) use ($user) {
                    $groupIds = Group::where('user_id', $user->id)
                        ->where('is_deleted', 0)
                        ->pluck('id')
                        ->toArray();

                    $query->where(function ($query) use ($groupIds) {
                        foreach ($groupIds as $groupId) {
                            $query->orWhereRaw("JSON_CONTAINS(group_ids, '\"$groupId\"')");
                        }
                    });
                })->get();
        }


        $posts = Result::where(function ($query) use ($request) {
            if ($request->search) {
                $query->where('correct_count', 'like', "%{$request->search}%");
            }
        })
            ->where(function ($query) use ($request) {
                if ($request->exam_id) {
                    $query->where('exam_id', $request->exam_id);
                }
            })
            ->where(function ($query) use ($request) {
                if ($request->customer_id) {
                    $query->where('customer_id', $request->customer_id);
                }
            })
            ->when(Auth::guard('teacher')->check(), function ($query) use ($user) {
                $groupIds = Group::where('user_id', $user->id)
                    ->where('is_deleted', 0)
                    ->pluck('id')
                    ->toArray();

                $videoCourseIds = Exam::where(function ($query) use ($groupIds) {
                    foreach ($groupIds as $groupId) {
                        $query->orWhereRaw("JSON_CONTAINS(group_ids, '\"$groupId\"')");
                    }
                })->pluck('id')->toArray();

                $query->whereIn('exam_id', $videoCourseIds);
            })
            ->orderBy('id', 'desc')
            ->paginate(20);

        return view('admin.pages.result', compact('posts', 'exams', 'customers'));
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
