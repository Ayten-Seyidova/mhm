<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GuestRequest;
use App\Models\Action;
use App\Models\Direction;
use App\Models\Guest;
use App\Models\SubDirection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class GuestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $directions = Direction::where('is_deleted', 0)->get();
        $allSubDirections = SubDirection::where('is_deleted', 0)->get();

        if ($request->direction_id) {
            $subDirections = SubDirection::where('direction_id', $request->direction_id)->where('is_deleted', 0)->get();
        } else {
            $subDirections = $allSubDirections;
        }

        $isDeleted = $request->is_deleted;

        if (!isset($isDeleted)) {
            $isDeleted = 0;
        }

        $posts = Guest::with('subDirection')->where('is_deleted', $isDeleted)->where(function ($query) use ($request) {
            return $request->search ?
                $query->from('search')->where('name', 'like', "%$request->search%")->orWhere('phone', 'like', "%$request->search%") : '';
        })->where(function ($query) use ($request) {
            return $request->status ?
                $query->from('status')->where('status', $request->status) : '';
        })->where(function ($query) use ($request) {
            return $request->sub_direction_id ?
                $query->from('sub_direction_id')->where('sub_direction_id', $request->sub_direction_id) : '';
        })->when($request->direction_id, function ($query) use ($request) {
            return $query->whereHas('subDirection', function($q) use ($request) {
                $q->where('direction_id', $request->direction_id);
            });
        })->where(function ($query) use ($request) {
            return $request->is_student ?
                $query->from('is_student')->where('is_student', $request->is_student) : '';
        })->orderBy('status', 'desc')->orderBy('id', 'desc')->paginate(50);

        return view('admin.pages.guest', compact('posts', 'directions', 'subDirections', 'allSubDirections'));
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
    public function store(GuestRequest $request)
    {
        $image = $request->file('image');
        Guest::create([
            'image' => $image ? uploadImg($image) : 'postImage/noUser.png',
            'name' => $request->name,
            'phone' => $request->phone,
          //  'password' => bcrypt('12345678'),
            'is_student' => isset($request->is_student) ? 1 : 0,
            'status' => isset($request->status) ? 1 : 0,
            'sub_direction_id' => $request->sub_direction_id,
        ]);

        if (Auth::guard('admin')->check()) {
            $user = Auth::guard('admin')->user();
            Action::create([
                'title' => $user->name . ' "' . $request->name . '" adlı yeni qonaq yaratdı.'
            ]);
        }

        alert()->success('Uğurlu', 'Əlavə olundu')
            ->showConfirmButton('Tamam', '#163A76');

        return redirect()->route('guest.index');
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
        $post = Guest::with('subDirection')->find($id);
        return response()->json(['post' => $post], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(GuestRequest $request, string $id)
    {
        $postUpdate = Guest::find($id);

        $image = $request->file('image');

        if ($_POST['hidden'] == "0") {
            $postUpdate->image = 'postImage/noUser.png';
        } else if ($image && $_POST['hidden'] == "1") {
            $postUpdate->image = uploadImg($image);
        }

        $postUpdate->name = $request->name;
        $postUpdate->phone = $request->phone;
        $postUpdate->sub_direction_id = $request->sub_direction_id;
        $postUpdate->is_student = isset($request->is_student) ? 1 : 0;
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
        $customer = Guest::find($id);
        if ($customer->is_deleted == 0) {
            $customer->is_deleted = 1;
            if (Auth::guard('admin')->check()) {
                $user = Auth::guard('admin')->user();
                Action::create([
                    'title' => $user->name . ' "' . $customer->name . '" adlı qonağı sildi.'
                ]);
            }
        } else {
            $customer->is_deleted = 0;
            if (Auth::guard('admin')->check()) {
                $user = Auth::guard('admin')->user();
                Action::create([
                    'title' => $user->name . ' "' . $customer->name . '" adlı silinmiş qonağı bərpa etdi.'
                ]);
            }
        }
        $customer->save();
        return response()->json(['message' => 'Uğurlu']);
    }

    public function changeStatus(Request $request)
    {
        try {
            $postID = $request->id;
            $post = Guest::find($postID);
            $status = $post->status;

            if ($status == 0) {
                if (Auth::guard('admin')->check()) {
                    $user = Auth::guard('admin')->user();
                    Action::create([
                        'title' => $user->name . ' "' . $post->name . '" adlı qonağı aktiv etdi.'
                    ]);
                }
            } else {
                if (Auth::guard('admin')->check()) {
                    $user = Auth::guard('admin')->user();
                    Action::create([
                        'title' => $user->name . ' "' . $post->name . '" adlı qonağı deaktiv etdi.'
                    ]);
                }
            }

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
                $post = Guest::find($id);
                $post->status = 0;
                $post->save();
                if (Auth::guard('admin')->check()) {
                    $user = Auth::guard('admin')->user();
                    Action::create([
                        'title' => $user->name . ' "' . $post->name . '" adlı qonağı deaktiv etdi.'
                    ]);
                }
            }
        } else if ($request->val == 1) {
            foreach ($arr as $id) {
                $post = Guest::find($id);
                $post->status = 1;
                $post->save();
                if (Auth::guard('admin')->check()) {
                    $user = Auth::guard('admin')->user();
                    Action::create([
                        'title' => $user->name . ' "' . $post->name . '" adlı qonağı aktiv etdi.'
                    ]);
                }
            }
        } else if ($request->val == 2) {
            foreach ($arr as $id) {
                $customer = Guest::find($id);
                if ($customer->is_deleted == 0) {
                    $customer->is_deleted = 1;
                    if (Auth::guard('admin')->check()) {
                        $user = Auth::guard('admin')->user();
                        Action::create([
                            'title' => $user->name . ' "' . $customer->name . '" adlı qonağı sildi.'
                        ]);
                    }
                } else {
                    $customer->is_deleted = 0;
                    if (Auth::guard('admin')->check()) {
                        $user = Auth::guard('admin')->user();
                        Action::create([
                            'title' => $user->name . ' "' . $customer->name . '" adlı silinmiş qonağı bərpa etdi.'
                        ]);
                    }
                }
                $customer->save();
            }
        }

        return response()->json(['message' => 'Uğurlu']);
    }

    public function download(Request $request)
    {
        $isDeleted = $request->is_deleted;

        if (!isset($isDeleted)) {
            $isDeleted = 0;
        }

        $query = Guest::with('subDirection')->where('is_deleted', $isDeleted)->where(function ($query) use ($request) {
            return $request->search ?
                $query->from('search')->where('name', 'like', "%$request->search%")->orWhere('phone', 'like', "%$request->search%") : '';
        })->where(function ($query) use ($request) {
            return $request->status ?
                $query->from('status')->where('status', $request->status) : '';
        })->where(function ($query) use ($request) {
            return $request->sub_direction_id ?
                $query->from('sub_direction_id')->where('sub_direction_id', $request->sub_direction_id) : '';
        })->when($request->direction_id, function ($query) use ($request) {
            return $query->whereHas('subDirection', function($q) use ($request) {
                $q->where('direction_id', $request->direction_id);
            });
        })->where(function ($query) use ($request) {
            return $request->is_student ?
                $query->from('is_student')->where('is_student', $request->is_student) : '';
        })->orderBy('status', 'desc')->orderBy('id', 'desc');

        $response = new StreamedResponse(function () use ($query) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, ['№', 'Şəkil', 'Ad və soyad', 'Telefon', 'Hazırlıq istiqaməti', 'İstiqamət', 'MHM tələbəsi', 'Status', 'Tarix']);


            $query->chunk(500, function ($ads) use ($handle) {
                foreach ($ads as $key => $parentModel) {
                    $image = $parentModel->image
                        ? '=HYPERLINK("' . asset($parentModel->image) . '", "Şəkil")'
                        : '';
                    $data = [
                        $key,
                        $image,
                        $parentModel->name,
                        $parentModel->phone,
                        optional(optional($parentModel->subDirection)->direction)->title,
                        optional($parentModel->subDirection)->title,
                        $parentModel->is_student === 1 ? "Bəli" : ($parentModel->is_student === 0 ? "Xeyr" : ''),
                        $parentModel->status === 1 ? "Aktiv" : ($parentModel->status === 0 ? "Deaktiv" : ''),
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
        $response->headers->set('Content-Disposition', 'attachment; filename="guest.csv"');

        return $response;
    }
}
