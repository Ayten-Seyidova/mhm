<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guest;
use App\Models\GuestExam;
use App\Models\GuestResult;
use App\Models\TeacherSubDirection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
        $posts = GuestResult::with('guest')->with('guestExam')->where(function ($query) use ($request) {
            if ($request->search) {
                $query->where('correct_count', 'like', "%{$request->search}%");
            }
        })->where(function ($query) use ($request) {
            if ($request->guest_exam_id) {
                $query->where('guest_exam_id', $request->guest_exam_id);
            }
        })->where(function ($query) use ($request) {
            if ($request->guest_id) {
                $query->where('guest_id', $request->guest_id);
            }
        });

        if (Auth::guard('teacher')->check()) {
            $user = Auth::guard('teacher')->user();
            $exams = GuestExam::where('status', 1)
                ->where('is_deleted', 0)
                ->where('user_id', $user->id)
                ->get();

            $teaherDirectionIds = TeacherSubDirection::where('user_id', $user->id)->pluck('sub_direction_id');
            $guests = Guest::where('is_deleted', 0)->whereIn('id', $teaherDirectionIds)->get();

            $userId = $user->id;
            $posts = $posts->when($userId, function ($query, $userId) {
                $query->whereHas('guestExam', function ($q) use ($userId) {
                    $q->where('user_id', $userId);
                });
            });
        }

        $posts = $posts->orderBy('id', 'desc')->paginate(20);

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

    public function download(Request $request)
    {
        $user = null;

        $exams = GuestExam::where('status', 1)->where('is_deleted', 0)->get();
        $guests = Guest::where('is_deleted', 0)->get();
        $posts = GuestResult::with('guest')->with('guestExam')->where(function ($query) use ($request) {
            if ($request->search) {
                $query->where('correct_count', 'like', "%{$request->search}%");
            }
        })->where(function ($query) use ($request) {
            if ($request->guest_exam_id) {
                $query->where('guest_exam_id', $request->guest_exam_id);
            }
        })->where(function ($query) use ($request) {
            if ($request->guest_id) {
                $query->where('guest_id', $request->guest_id);
            }
        });

        if (Auth::guard('teacher')->check()) {
            $user = Auth::guard('teacher')->user();
            $exams = GuestExam::where('status', 1)
                ->where('is_deleted', 0)
                ->where('user_id', $user->id)
                ->get();

            $teaherDirectionIds = TeacherSubDirection::where('user_id', $user->id)->pluck('sub_direction_id');
            $guests = Guest::where('is_deleted', 0)->whereIn('id', $teaherDirectionIds)->get();

            $userId = $user->id;
            $posts = $posts->when($userId, function ($query, $userId) {
                $query->whereHas('guestExam', function ($q) use ($userId) {
                    $q->where('user_id', $userId);
                });
            });
        }

        $query = $posts->orderBy('id', 'desc');

        $response = new StreamedResponse(function () use ($query) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, ['İmtahan', 'Qonaq', 'Bal', 'Düzgün cavab sayı', 'Səhv cavab sayı', 'Vaxt', 'Yaranma tarixi']);


            $query->chunk(500, function ($ads) use ($handle) {
                foreach ($ads as $key => $parentModel) {
                    $data = [
                        optional($parentModel->guestExam)->name,
                        optional($parentModel->guest)->name,
                        $parentModel->point,
                        $parentModel->correct_count,
                        $parentModel->incorrect_count,
                        $parentModel->time,
                        $parentModel->created_at,
                    ];

                    array_walk($data, function (&$item) {
                        $item = mb_convert_encoding($item, 'UTF-8', 'UTF-8');
                    });

                    fputcsv($handle, $data);
                }
            });

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="guest-result.csv"');

        return $response;
    }

}
