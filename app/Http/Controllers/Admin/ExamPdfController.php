<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GuestExam;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\Guest;

class ExamPdfController extends Controller
{
    private function buildData(Request $request): array
    {
        $guestId = $request->query('guest_id');
        $examId  = $request->query('guest_exam_id');

        $guest     = $guestId ? Guest::find($guestId) : null;
        $guestExam = $examId ? GuestExam::find($examId) : null;

        $name = $guest->name ?? (string) $request->query('name', 'John Doe');
        $exam = $guestExam->name ?? (string) $request->query('exam', 'XXX');

        $score = (int)$request->query('score', 5);
        $correct = (int)$request->query('correct', $score);
        $wrong = (int)$request->query('wrong', 0);
        $duration = trim((string)$request->query('duration'));

        return compact('name', 'exam', 'score', 'correct', 'wrong', 'duration');
    }

    public function preview(Request $request)
    {
        $data = $this->buildData($request);
        return view('admin.pages.pdf-exam', $data);
    }

    public function pdf(Request $request)
    {
//        $data = $this->buildData($request);
//        $html = View::make('admin.pages.pdf-exam', $data)->render();
//
//        return Pdf::loadView('admin.pages.pdf-exam', $data)
//            ->setPaper('a4')
//            ->stream('imtahan-neticesi.pdf');
//
//        return view('admin.pages.pdf-exam', $data);

        $data = $this->buildData($request);

        $pdf = Pdf::loadView('admin.pages.pdf-exam', $data)
            ->setPaper('a4');

        $fileName = 'imtahan-neticesi-' . time() . '.pdf';
        $filePath = public_path('pdf/' . $fileName);

        if (!file_exists(public_path('pdf'))) {
            mkdir(public_path('pdf'), 0777, true);
        }

        $pdf->save($filePath);

        return response()->json([
            'file' => asset('pdf/' . $fileName)
        ]);
    }
}
