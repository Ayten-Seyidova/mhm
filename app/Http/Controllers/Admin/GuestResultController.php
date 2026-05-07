<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guest;
use App\Models\GuestExam;
use App\Models\GuestResult;
use App\Models\TeacherSubDirection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

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

    public function index(Request $request)
    {
        $exams = GuestExam::where('status', 1)->where('is_deleted', 0)->get();

        $selectedGuest = null;
        if ($request->customer_id) {
            $selectedGuest = Guest::where('is_deleted', 0)
                ->where('id', $request->customer_id)
                ->first();
        }

        if (Auth::guard('teacher')->check()) {
            $user = Auth::guard('teacher')->user();
            $exams = GuestExam::where('status', 1)
                ->where('is_deleted', 0)
                ->where('user_id', $user->id)
                ->get();
        }

        $posts = $this->buildQuery($request)
            ->orderBy('id', 'desc')
            ->paginate(20);

        return view('admin.pages.guest-result', compact('posts', 'exams', 'selectedGuest'));
    }

    /**
     * AJAX endpoint - Select2 üçün qonaq axtarışı
     */
    public function searchGuests(Request $request)
    {
        $search = trim($request->get('q', ''));
        $page = (int) $request->get('page', 1);
        $perPage = 30;

        $query = Guest::where('is_deleted', 0);

        if (Auth::guard('teacher')->check()) {
            $user = Auth::guard('teacher')->user();
            $teacherDirectionIds = TeacherSubDirection::where('user_id', $user->id)
                ->pluck('sub_direction_id');
            $query->whereIn('id', $teacherDirectionIds);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $total = $query->count();
        $guests = $query->orderBy('name')
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get(['id', 'name', 'phone']);

        $results = $guests->map(function ($guest) {
            $text = $guest->name;
            if (!empty($guest->phone)) {
                $text .= ' — ' . $guest->phone;
            }
            return [
                'id' => $guest->id,
                'text' => $text,
            ];
        });

        return response()->json([
            'results' => $results,
            'pagination' => [
                'more' => ($page * $perPage) < $total,
            ],
        ]);
    }

    public function create() {}
    public function store(Request $request) {}
    public function show(string $id) {}
    public function edit(string $id) {}
    public function update(Request $request, string $id) {}
    public function destroy(string $id) {}

    /**
     * Download results as styled XLSX (Excel) file.
     */
    public function download(Request $request)
    {
        // Yaddaşı və vaxtı artırırıq, böyük export-lar üçün
        ini_set('memory_limit', '512M');
        set_time_limit(600);

        $query = $this->buildQuery($request)->orderBy('id', 'desc');

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Nəticələr');

        // ---------- Başlıqlar ----------
        $headers = [
            '#',
            'İmtahan',
            'Qonaq',
            'Telefon',
            'E-poçt',
            'Bal',
            'Düzgün cavab',
            'Səhv cavab',
            'Vaxt',
            'Yaranma tarixi',
        ];

        $sheet->fromArray($headers, null, 'A1');

        // ---------- Başlıq stili ----------
        $headerRange = 'A1:' . $sheet->getHighestColumn() . '1';
        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 11,
                'name' => 'Calibri',
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4D6CFA'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'E6E6E6'],
                ],
            ],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(28);

        // ---------- Datanı sətr-sətr yazırıq (memory friendly) ----------
        $row = 2;
        $counter = 1;

        $query->chunk(500, function ($items) use ($sheet, &$row, &$counter) {
            foreach ($items as $item) {
                $createdAt = $item->created_at
                    ? $item->created_at->format('d.m.Y H:i')
                    : '';

                $sheet->setCellValue('A' . $row, $counter++);
                $sheet->setCellValue('B' . $row, optional($item->guestExam)->name);
                $sheet->setCellValue('C' . $row, optional($item->guest)->name);

                // Telefonu mətn kimi yazırıq ki Excel başdakı 0-ı silməsin
                $phone = optional($item->guest)->phone;
                $sheet->setCellValueExplicit(
                    'D' . $row,
                    $phone ?? '',
                    \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
                );

                $sheet->setCellValue('E' . $row, optional($item->guest)->email);
                $sheet->setCellValue('F' . $row, $item->point);
                $sheet->setCellValue('G' . $row, $item->correct_count);
                $sheet->setCellValue('H' . $row, $item->incorrect_count);
                $sheet->setCellValue('I' . $row, $item->time);
                $sheet->setCellValue('J' . $row, $createdAt);

                $row++;
            }
        });

        $lastRow = $row - 1;

        // ---------- Data sətirləri stili ----------
        if ($lastRow >= 2) {
            $dataRange = 'A2:J' . $lastRow;

            $sheet->getStyle($dataRange)->applyFromArray([
                'font' => [
                    'size' => 10,
                    'name' => 'Calibri',
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'EEEEEE'],
                    ],
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ]);

            // Mərkəzdə olan sütunlar (#, Bal, Düzgün, Səhv, Vaxt)
            $sheet->getStyle('A2:A' . $lastRow)->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('F2:I' . $lastRow)->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('J2:J' . $lastRow)->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Zebra striping (cüt sətirlər - boz fon)
            for ($r = 2; $r <= $lastRow; $r++) {
                if ($r % 2 == 0) {
                    $sheet->getStyle('A' . $r . ':J' . $r)->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'F8F9FC'],
                        ],
                    ]);
                }
            }
        }

        // ---------- Sütun genişlikləri ----------
        $sheet->getColumnDimension('A')->setWidth(6);   // #
        $sheet->getColumnDimension('B')->setWidth(35);  // İmtahan
        $sheet->getColumnDimension('C')->setWidth(28);  // Qonaq
        $sheet->getColumnDimension('D')->setWidth(18);  // Telefon
        $sheet->getColumnDimension('E')->setWidth(28);  // E-poçt
        $sheet->getColumnDimension('F')->setWidth(10);  // Bal
        $sheet->getColumnDimension('G')->setWidth(14);  // Düzgün
        $sheet->getColumnDimension('H')->setWidth(14);  // Səhv
        $sheet->getColumnDimension('I')->setWidth(12);  // Vaxt
        $sheet->getColumnDimension('J')->setWidth(20);  // Tarix

        // ---------- Freeze pane (başlıq sabitlənsin) ----------
        $sheet->freezePane('A2');

        // ---------- AutoFilter (Excel-də filter düymələri) ----------
        if ($lastRow >= 2) {
            $sheet->setAutoFilter('A1:J' . $lastRow);
        }

        // ---------- Cavabı qaytaraq ----------
        $filename = 'neticeler-' . date('Y-m-d_H-i-s') . '.xlsx';

        $response = new \Symfony\Component\HttpFoundation\StreamedResponse(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        });

        $response->headers->set(
            'Content-Type',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        );
        $response->headers->set(
            'Content-Disposition',
            'attachment; filename="' . $filename . '"'
        );
        $response->headers->set('Cache-Control', 'max-age=0');
        $response->headers->set('Pragma', 'no-cache');

        return $response;
    }
}
