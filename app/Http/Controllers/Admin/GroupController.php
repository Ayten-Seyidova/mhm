<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GroupRequest;
use App\Models\Action;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $teachers = User::where('type', 'teacher')->where('status', 1)->where('is_deleted', 0)->get();
        $isDeleted = $request->is_deleted;
        if (!isset($isDeleted)) {
            $isDeleted = 0;
        }
        $posts = Group::where('is_deleted', $isDeleted)->where(function ($query) use ($request) {
            return $request->search ?
                $query->from('search')->where('name', 'like', "%$request->search%") : '';
        })->where(function ($query) use ($request) {
            return $request->user_id ?
                $query->from('user_id')->where('user_id', $request->user_id) : '';
        })->orderBy('id', 'desc')->paginate(20);

        return view('admin.pages.group', compact('posts', 'teachers'));
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
    public function store(GroupRequest $request)
    {
        $image = $request->file('image');
        Group::create([
            'image' => $image ? uploadImg($image) : 'postImage/noPhoto.png',
            'name' => $request->name,
            'user_id' => $request->user_id,
        ]);

        if (Auth::guard('admin')->check()) {
            $user = Auth::guard('admin')->user();
            Action::create([
                'title' => $user->name . ' "' . $request->name . '" adlı qrupu yaratdı.'
            ]);
        }

        alert()->success('Uğurlu', 'Əlavə olundu')
            ->showConfirmButton('Tamam', '#163A76');

        return redirect()->route('group.index');
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
        $post = Group::find($id);
        return response()->json(['post' => $post], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(GroupRequest $request, string $id)
    {
        $postUpdate = Group::find($id);

        $image = $request->file('image');

        if ($_POST['hidden'] == "0") {
            $postUpdate->image = 'postImage/noPhoto.png';
        } else if ($image && $_POST['hidden'] == "1") {
            $postUpdate->image = uploadImg($image);
        }

        $postUpdate->name = $request->name;
        $postUpdate->user_id = $request->user_id;

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
        $customer = Group::find($id);
        if ($customer->is_deleted == 0) {
            $customer->is_deleted = 1;
            if (Auth::guard('admin')->check()) {
                $user = Auth::guard('admin')->user();
                Action::create([
                    'title' => $user->name . ' "' . $customer->name . '" adlı qrupu sildi.'
                ]);
            }
        } else {
            $customer->is_deleted = 0;
            if (Auth::guard('admin')->check()) {
                $user = Auth::guard('admin')->user();
                Action::create([
                    'title' => $user->name . ' "' . $customer->name . '" adlı silinmiş qrupu bərpa etdi.'
                ]);
            }
        }

        $customer->save();
        return response()->json(['message' => 'Uğurlu']);
    }

    public function checked(Request $request)
    {
        $arr = $request->arr;

        foreach ($arr as $id) {
            $customer = Group::find($id);
            if ($customer->is_deleted == 0) {
                $customer->is_deleted = 1;
                if (Auth::guard('admin')->check()) {
                    $user = Auth::guard('admin')->user();
                    Action::create([
                        'title' => $user->name . ' "' . $customer->name . '" adlı qrupu sildi.'
                    ]);
                }
            } else {
                $customer->is_deleted = 0;
                if (Auth::guard('admin')->check()) {
                    $user = Auth::guard('admin')->user();
                    Action::create([
                        'title' => $user->name . ' "' . $customer->name . '" adlı silinmiş qrupu bərpa etdi.'
                    ]);
                }
            }
            $customer->save();
        }

        return response()->json(['message' => 'Uğurlu']);
    }
}
