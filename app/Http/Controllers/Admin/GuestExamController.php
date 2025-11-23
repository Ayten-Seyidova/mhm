<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GuestExamRequest;
use App\Models\GuestExam;
use App\Models\GuestExamSubDirection;
use App\Models\GuestQuestion;
use App\Models\SubDirection;
use App\Models\TeacherSubDirection;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class GuestExamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = null;

        $teachers = User::where('type', 'teacher')->get();

        $allSubDirections = SubDirection::where('is_deleted', 0)->get();

        if ($request->direction_id) {
            $subDirections = SubDirection::where('direction_id', $request->direction_id)->where('is_deleted', 0)->get();
        } else {
            $subDirections = $allSubDirections;
        }

        $isDeleted = $request->is_deleted ?? 0;

        $posts = GuestExam::with('teacher')->with('guestExamSubDirections')->where('is_deleted', $isDeleted)
            ->where(function ($query) use ($request) {
                if ($request->search) {
                    $query->where('name', 'like', "%{$request->search}%")->orWhere('description', 'like', "%{$request->search}%");
                }
            })
            ->where(function ($query) use ($request) {
                return $request->user_id ?
                    $query->from('user_id')->where('user_id', $request->user_id) : '';
            })
            ->when($request->sub_direction_id, function ($query) use ($request) {
                $query->whereHas('guestExamSubDirections', function ($q) use ($request) {
                    $q->where('sub_direction_id', $request->sub_direction_id);
                });
            })
            ->where(function ($query) use ($request) {
                return $request->status ?
                    $query->from('status')->where('status', $request->status) : '';
            });

        if (Auth::guard('teacher')->check()) {
            $user = Auth::guard('teacher')->user();

            $posts = $posts->where('user_id', $user->id);

            $teaherDirectionIds = TeacherSubDirection::where('user_id', $user->id)->pluck('sub_direction_id');
            $subDirections = SubDirection::whereIn('id', $teaherDirectionIds)->get();
        }

        $posts = $posts->orderBy('id', 'desc')->paginate(20);
        return view('admin.pages.guest-exam', compact('posts', 'subDirections', 'allSubDirections', 'teachers'));
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
    public function store(GuestExamRequest $request)
    {
        $image = $request->file('image');

        $userId = null;
        if (Auth::guard('teacher')->check()) {
            $user = Auth::guard('teacher')->user();
            $userId = $user->id;
        }

        $url = $request->desc_video;
        $position = strpos($url, '=');

        if ($position !== false) {
            $parsedUrl = parse_url($url);

            parse_str($parsedUrl['query'] ?? '', $queryParams);

            $videoId = $queryParams['v'] ?? null;

            if ($videoId) {
                $url = $videoId;
            } else {
                $url = '';
            }
        }

        $url2 = $request->desc_video2;
        $position2 = strpos($url2, '=');

        if ($position2 !== false) {
            $parsedUrl2 = parse_url($url2);

            parse_str($parsedUrl2['query'] ?? '', $queryParams2);

            $videoId2 = $queryParams2['v'] ?? null;

            if ($videoId2) {
                $url2 = $videoId2;
            } else {
                $url2 = '';
            }
        }

        $post = GuestExam::create([
            'image' => $image ? uploadImg($image) : 'postImage/noPhoto.png',
            'description' => $request->description,
            'time' => $request->time ? $request->time : null,
            'desc_video' => $url,
            'desc_video2' => $url2,
            'user_id' => $userId,
            'status' => isset($request->status) ? 1 : 0,
            'name' => $request->name,
            'subject' => $request->subject,
            'percent' => $request->percent,
            'duration' => $request->duration,
        ]);

        $subDirectionIds = $request->sub_direction_ids;
        if (!empty($subDirectionIds)) {
            foreach ($subDirectionIds as $subDirectionId) {
                GuestExamSubDirection::create([
                    'guest_exam_id' => $post->id,
                    'sub_direction_id' => $subDirectionId,
                ]);
            }
        }

        alert()->success('Uğurlu', 'Əlavə olundu')
            ->showConfirmButton('Tamam', '#163A76');

        return redirect()->route('guest-exam.index');
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
        $post = GuestExam::find($id);
        $subDirections = GuestExamSubDirection::where('guest_exam_id', $post->id)->get()->pluck('sub_direction_id');
        return response()->json(['post' => $post, 'subDirections' => $subDirections], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(GuestExamRequest $request, string $id)
    {
        $postUpdate = GuestExam::find($id);

        $image = $request->file('image');

        if ($_POST['hidden'] == "0") {
            $postUpdate->image = 'postImage/noUser.png';
        } else if ($image && $_POST['hidden'] == "1") {
            $postUpdate->image = uploadImg($image);
        }

        $url = $request->desc_video;
        $position = strpos($url, '=');

        if ($position !== false) {
            $parsedUrl = parse_url($url);

            parse_str($parsedUrl['query'] ?? '', $queryParams);

            $videoId = $queryParams['v'] ?? null;

            if ($videoId) {
                $url = $videoId;
            }
        }


        $url2 = $request->desc_video2;
        $position2 = strpos($url2, '=');

        if ($position2 !== false) {
            $parsedUrl2 = parse_url($url2);

            parse_str($parsedUrl2['query'] ?? '', $queryParams2);

            $videoId2 = $queryParams2['v'] ?? null;

            if ($videoId2) {
                $url2 = $videoId2;
            }
        }

        $postUpdate->desc_video = $url;
        $postUpdate->desc_video2 = $url2;
        $postUpdate->name = $request->name;
        $postUpdate->time = $request->time;
        $postUpdate->description = $request->description;
        $postUpdate->duration = $request->duration;
        $postUpdate->percent = $request->percent;
        $postUpdate->subject = $request->subject;
        $postUpdate->status = isset($request->status) ? 1 : 0;

        $postUpdate->save();

        GuestExamSubDirection::where('guest_exam_id', $postUpdate->id)->delete();

        $subDirectionIds = $request->sub_direction_ids;
        if (!empty($subDirectionIds)) {
            foreach ($subDirectionIds as $subDirectionId) {
                GuestExamSubDirection::create([
                    'guest_exam_id' => $postUpdate->id,
                    'sub_direction_id' => $subDirectionId,
                ]);
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
        $customer = GuestExam::find($id);
        if ($customer->is_deleted == 0) {
            $customer->is_deleted = 1;
        } else {
            $customer->is_deleted = 0;
        }
        $customer->save();
        return response()->json(['message' => 'Uğurlu']);
    }

    public function changeStatus(Request $request)
    {
        try {
            $postID = $request->id;
            $post = GuestExam::find($postID);
            $status = $post->status;
            $post->status = $status ? 0 : 1;

            $post->save();

            return response()->json(['message' => 'Uğurlu', 'status' => $post->status], 200);
        } catch (\Exception $exception) {
            return response()->json(['message' => 'Xəta', 'status' => $post->status], 500);
        }
    }

    public function checked(Request $request)
    {
        $arr = $request->arr;

        if ($request->val == 0) {
            foreach ($arr as $id) {
                $post = GuestExam::find($id);
                $post->status = 0;
                $post->save();
            }
        } else if ($request->val == 1) {
            foreach ($arr as $id) {
                $post = GuestExam::find($id);
                $post->status = 1;
                $post->save();
            }
        } else {
            foreach ($arr as $id) {
                $customer = GuestExam::find($id);
                if ($customer->is_deleted == 0) {
                    $customer->is_deleted = 1;
                } else {
                    $customer->is_deleted = 0;
                }
                $customer->save();
            }
        }

        return response()->json(['message' => 'Uğurlu']);
    }

//    public function downloadPdf(Request $request)
//    {
//        ini_set('memory_limit', '4096M');
//        set_time_limit(0);
//        $user = null;
//        $guestExamId = $request->exam_id;
//        if (isset($guestExamId) && is_numeric($guestExamId)) {
//            $exam = GuestExam::find($request->exam_id);
//
//            if (!empty($exam)) {
//                if (Auth::guard('teacher')->check()) {
//                    $user = Auth::guard('teacher')->user();
//                    if ($exam->user_id != $user->id) {
//                        abort(403);
//                    }
//                }
//
//                $posts = GuestQuestion::where('is_deleted', 0)->where('guest_exam_id', $guestExamId)
//                    ->orderBy('type', 'desc')->orderBy('id', 'desc')->select(['id', 'title', 'title_type', 'variant_type', 'A', 'B', 'C', 'D', 'E', 'correct'])
//                    ->lazy();;
//
//                if ($posts->isNotEmpty()) {
//                    $pdf = \PDF::loadView('admin.pages.guest-exam-pdf', ['posts' => $posts, 'exam' => $exam])->setPaper('a4', 'portrait')
//                        ->setOptions([
//                            'isHtml5ParserEnabled' => true,
//                            'isRemoteEnabled' => true,
//                            'defaultFont' => 'DejaVu Sans',
//                            'chroot' => public_path(),
//                        ])
//                        ->setWarnings(false);
//                    $pdf->setBasePath(public_path());
//                    $pdfPath = storage_path('app/public/guest-exam.pdf');
//                    $pdf->save($pdfPath);
//
//                    $file = 'guest-exam.pdf';
//
//                    return redirect()->to(asset('storage/' . $file));
//                } else {
//                    alert()->error('Uğurlu', 'İmtahana aid sual tapılmadı')
//                        ->showConfirmButton('Tamam', '#163A76');
//
//                    return redirect()->back();
//                }
//
//                return redirect()->back()->with('error', 'İmtahan tapılmadı.');
//            } else {
//                abort(404);
//            }
//        } else {
//            abort(404);
//        }
//    }

    public function downloadPdf(Request $request)
    {
        ini_set('memory_limit', '4096M');
        set_time_limit(0);

        $guestExamId = $request->exam_id;
        if (!isset($guestExamId) || !is_numeric($guestExamId)) {
            abort(404);
        }

        $exam = GuestExam::find($guestExamId);
        if (!$exam) {
            abort(404);
        }

        if (Auth::guard('teacher')->check()) {
            $user = Auth::guard('teacher')->user();
            if ($exam->user_id != $user->id) {
                abort(403);
            }
        }

        $posts = GuestQuestion::where('is_deleted', 0)
            ->where('guest_exam_id', $guestExamId)
            ->orderBy('type', 'desc')
            ->orderBy('id', 'desc')
            ->select(['id','title','title_type','variant_type','A','B','C','D','E','correct'])
            ->get();

        if ($posts->isEmpty()) {
            alert()->error('Uğursuz', 'İmtahana aid sual tapılmadı')
                ->showConfirmButton('Tamam', '#163A76');
            return back();
        }

        $pdf = Pdf::loadView('admin.pages.guest-exam-pdf', [
            'posts' => $posts,
            'exam'  => $exam
        ])->setPaper('a4', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'DejaVu Sans',
                'chroot' => public_path(),
            ])->setWarnings(false);

        $pdf->setBasePath(public_path());
        $content = $pdf->output();

        return response($content, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="guest-exam-'.$guestExamId.'.pdf"',
            'Cache-Control'       => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma'              => 'no-cache',
            'Expires'             => '0',
        ]);
    }
}
