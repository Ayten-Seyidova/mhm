<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GuestQuestionRequest;
use App\Models\GuestExam;
use App\Models\GuestQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuestQuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = null;
        $guestExamId = $request->guest_exam_id;
        $isDeleted = $request->is_deleted ?? 0;
        if (isset($guestExamId) && is_numeric($guestExamId)) {
            $exam = GuestExam::find($request->guest_exam_id);

            if (!empty($exam)) {
                if (Auth::guard('teacher')->check()) {
                    $user = Auth::guard('teacher')->user();
                    if ($exam->user_id != $user->id) {
                        abort(403);
                    }
                }

                $posts = GuestQuestion::where('is_deleted', $isDeleted)->where('guest_exam_id', $guestExamId)
                    ->where(function ($query) use ($request) {
                        if ($request->search) {
                            $query->where('title', 'like', "%{$request->search}%");
                        }
                    })->where(function ($query) use ($request) {
                        return $request->status ?
                            $query->from('status')->where('status', $request->status) : '';
                    })->orderBy('status', 'desc')->orderBy('id', 'desc')->paginate(20);

                return view('admin.pages.guest-question', compact('posts', 'exam'));
            } else {
                abort(404);
            }
        } else {
            abort(404);
        }
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
    public function store(GuestQuestionRequest $request)
    {
        $image = $request->file('image');
        $image = $image ? uploadImg($image) : 'postImage/noPhoto.png';
        $image1 = $request->file('image1');
        $image1 = $image1 ? uploadImg($image1) : '';
        $image2 = $request->file('image2');
        $image2 = $image2 ? uploadImg($image2) : '';
        $image3 = $request->file('image3');
        $image3 = $image3 ? uploadImg($image3) : '';
        $image4 = $request->file('image4');
        $image4 = $image4 ? uploadImg($image4) : '';
        $image5 = $request->file('image5');
        $image5 = $image5 ? uploadImg($image5) : '';

        GuestQuestion::create([
            'title' => $request->title_type == 'text' ? str_replace(['<iframe', '&#39;'], ['<iframe allowfullscreen', "'"], $request->title) : $image,
            'A' => $request->variant_type == 'text' ? str_replace(['<iframe', '&#39;'], ['<iframe allowfullscreen', "'"], $request->A) : $image1,
            'B' => $request->variant_type == 'text' ? str_replace(['<iframe', '&#39;'], ['<iframe allowfullscreen', "'"], $request->B) : $image2,
            'C' => $request->variant_type == 'text' ? str_replace(['<iframe', '&#39;'], ['<iframe allowfullscreen', "'"], $request->C) : $image3,
            'D' => $request->variant_type == 'text' ? str_replace(['<iframe', '&#39;'], ['<iframe allowfullscreen', "'"], $request->D) : $image4,
            'E' => $request->variant_type == 'text' ? str_replace(['<iframe', '&#39;'], ['<iframe allowfullscreen', "'"], $request->E) : $image5,
            'status' => isset($request->status) ? 1 : 0,
            'correct' => $request->correct,
            'title_type' => $request->title_type,
            'variant_type' => $request->variant_type,
            'guest_exam_id' => $request->exam_id,
        ]);

        alert()->success('Uğurlu', 'Əlavə olundu')
            ->showConfirmButton('Tamam', '#163A76');

        return redirect()->back();
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
        $post = GuestQuestion::find($id);
        return response()->json(['post' => $post], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(GuestQuestionRequest $request, string $id)
    {
        $postUpdate = GuestQuestion::find($id);

        $image = $request->file('image');

        if ($_POST['hidden'] == "0") {
            $image = 'postImage/noPhoto.png';
        } else if ($image && $_POST['hidden'] == "1") {
            $image = uploadImg($image);
        } else if (!$image) {
            $image = $postUpdate->title;
        }

        $image1 = $request->file('image1');

        if ($_POST['hidden1'] == "0") {
            $image1 = '';
        } else if ($image1 && $_POST['hidden1'] == "1") {
            $image1 = uploadImg($image1);
        } else if (!$image1) {
            $image1 = $postUpdate->A;
        }

        $image2 = $request->file('image2');

        if ($_POST['hidden2'] == "0") {
            $image2 = '';
        } else if ($image2 && $_POST['hidden2'] == "1") {
            $image2 = uploadImg($image2);
        } else if (!$image2) {
            $image2 = $postUpdate->B;
        }

        $image3 = $request->file('image3');

        if ($_POST['hidden3'] == "0") {
            $image3 = '';
        } else if ($image3 && $_POST['hidden3'] == "1") {
            $image3 = uploadImg($image3);
        } else if (!$image3) {
            $image3 = $postUpdate->C;
        }

        $image4 = $request->file('image4');

        if ($_POST['hidden4'] == "0") {
            $image4 = '';
        } else if ($image4 && $_POST['hidden4'] == "1") {
            $image4 = uploadImg($image4);
        } else if (!$image4) {
            $image4 = $postUpdate->D;
        }

        $image5 = $request->file('image5');

        if ($_POST['hidden5'] == "0") {
            $image5 = '';
        } else if ($image5 && $_POST['hidden5'] == "1") {
            $image5 = uploadImg($image5);
        } else if (!$image5) {
            $image5 = $postUpdate->E;
        }

        $postUpdate->title = $request->title_type == 'text' ? str_replace(['<iframe', '&#39;'], ['<iframe allowfullscreen', "'"], $request->title) : $image;
        $postUpdate->A = $request->variant_type == 'text' ? str_replace(['<iframe', '&#39;'], ['<iframe allowfullscreen', "'"], $request->A) : $image1;
        $postUpdate->B = $request->variant_type == 'text' ? str_replace(['<iframe', '&#39;'], ['<iframe allowfullscreen', "'"], $request->B) : $image2;
        $postUpdate->C = $request->variant_type == 'text' ? str_replace(['<iframe', '&#39;'], ['<iframe allowfullscreen', "'"], $request->C) : $image3;
        $postUpdate->D = $request->variant_type == 'text' ? str_replace(['<iframe', '&#39;'], ['<iframe allowfullscreen', "'"], $request->D) : $image4;
        $postUpdate->E = $request->variant_type == 'text' ? str_replace(['<iframe', '&#39;'], ['<iframe allowfullscreen', "'"], $request->E) : $image5;
        $postUpdate->correct = $request->correct;
        $postUpdate->variant_type = $request->variant_type;
        $postUpdate->title_type = $request->title_type;
        $postUpdate->status = isset($request->status) ? 1 : 0;

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
        $customer = GuestQuestion::find($id);
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
            $post = GuestQuestion::find($postID);
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
                $post = GuestQuestion::find($id);
                $post->status = 0;
                $post->save();
            }
        } else if ($request->val == 1) {
            foreach ($arr as $id) {
                $post = GuestQuestion::find($id);
                $post->status = 1;
                $post->save();
            }
        } else {
            foreach ($arr as $id) {
                $customer = GuestQuestion::find($id);
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
}
