<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class ExamPdfController extends Controller
{
    private function buildData(Request $request): array
    {
        $name = trim((string)$request->query('name', 'Nail Seyidov'));
        $exam = trim((string)$request->query('exam', 'XXX'));
        $score = (int)$request->query('score', 5);
        $correct = (int)$request->query('correct', $score);
        $wrong = (int)$request->query('wrong', 0);
        $duration = trim((string)$request->query('duration', '15 dəqiqə'));
        $status = trim((string)$request->query('status', 'Uğurlu'));

        return compact('name', 'exam', 'score', 'correct', 'wrong', 'duration', 'status');
    }

    public function preview(Request $request)
    {
        $data = $this->buildData($request);
        return view('admin.pages.pdf-exam', $data);
    }

    public function pdf(Request $request)
    {
        $data = $this->buildData($request);
        $html = View::make('admin.pages.pdf-exam', $data)->render();

        return Pdf::loadView('admin.pages.pdf-exam', $data)
            ->setPaper('a4')
            ->stream('imtahan-neticesi.pdf');

        return view('admin.pages.pdf-exam', $data);
    }
}
