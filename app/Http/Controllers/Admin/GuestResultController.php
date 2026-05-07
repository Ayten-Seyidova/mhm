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
     * Build the base query used by both index and download.
     */
    private function buildQuery(Request $request)
    {
        $query = GuestResult::with('guest')
            ->with('guestExam')
            ->when($request->search, function ($q) use ($request) {
                $q->where(function ($qq) use ($request) {
                    $qq->where('correct_count', 'like', "%{$request->search}%")
                        ->orWhere('incorrect_count', 'like', "%{$request->search}%")
                        ->orWhere('point', 'like', "%{$request->search}%")
                        ->orWhereHas('guest', function ($g) use ($request) {
                            $g->where('name', 'like', "%{$request->search}%")
                                ->orWhere('phone', 'like', "%{$request->search}%")
                                ->orWhere('email', 'like', "%{$request->search}%");
                        })
                        ->orWhereHas('guestExam', function ($e) use ($request) {
                            $e->where('name', 'like', "%{$request->search}%");
                        });
                });
            })
            ->when($request->exam_id, function ($q) use ($request) {
                $q->where('guest_exam_id', $request->exam_id);
            })
            ->when($request->customer_id, function ($q) use ($request) {
                $q->where('guest_id', $request->customer_id);
            });

        if (Auth::guard('teacher')->check()) {
            $user = Auth::guard('teacher')->user();
            $userId = $user->id;
            $query->whereHas('guestExam', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            });
        }

        return $query;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
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

        $posts = $this->buildQuery($request)
            ->orderBy('id', 'desc')
            ->paginate(20);

        return view('admin.pages.guest-result', compact('posts', 'exams', 'guests'));
    }

    public function create() {}
    public function store(Request $request) {}
    public function show(string $id) {}
    public function edit(string $id) {}
    public function update(Request $request, string $id) {}
    public function destroy(string $id) {}

    /**
     * Download results as CSV/Excel.
     */
    public function download(Request $request)
    {
        $query = $this->buildQuery($request)->orderBy('id', 'desc');

        $filename = 'guest-result-' . date('Y-m-d_H-i-s') . '.csv';

        $response = new StreamedResponse(function () use ($query) {
            $handle = fopen('php://output', 'w');
            // BOM for UTF-8 (Excel-də Azərbaycan hərfləri düzgün görünsün deyə)
            fwrite($handle, "\xEF\xBB\xBF");

            // Başlıqlar
            fputcsv($handle, [
                '#',
                'İmtahan',
                'Qonaq',
                'Telefon',
                'E-poçt',
                'Bal',
                'Düzgün cavab sayı',
                'Səhv cavab sayı',
                'Vaxt',
                'Yaranma tarixi',
            ]);

            $counter = 1;
            $query->chunk(500, function ($items) use ($handle, &$counter) {
                foreach ($items as $parentModel) {
                    $createdAt = $parentModel->created_at
                        ? $parentModel->created_at->format('d.m.Y H:i')
                        : '';

                    $data = [
                        $counter++,
                        optional($parentModel->guestExam)->name,
                        optional($parentModel->guest)->name,
                        optional($parentModel->guest)->phone,
                        optional($parentModel->guest)->email,
                        $parentModel->point,
                        $parentModel->correct_count,
                        $parentModel->incorrect_count,
                        $parentModel->time,
                        $createdAt,
                    ];

                    array_walk($data, function (&$item) {
                        $item = mb_convert_encoding((string) $item, 'UTF-8', 'UTF-8');
                    });

                    fputcsv($handle, $data);
                }
            });

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

        return $response;
    }
}
