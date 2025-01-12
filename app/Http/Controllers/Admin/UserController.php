<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $isDeleted = $request->is_deleted;
        if (!isset($isDeleted)) {
            $isDeleted = 0;
        }
        $posts = User::where('is_deleted', $isDeleted)->where(function ($query) use ($request) {
            return $request->search ?
                $query->from('search')->where('name', 'like', "%$request->search%")->orWhere('email', 'like', "%$request->search%") : '';
        })->where(function ($query) use ($request) {
            return $request->status ?
                $query->from('status')->where('status', $request->status) : '';
        })->where(function ($query) use ($request) {
            return $request->type ?
                $query->from('type')->where('type', $request->type) : '';
        })->orderBy('status', 'desc')->orderBy('id', 'desc')->paginate(20);

        return view('admin.pages.user', compact('posts'));
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
    public function store(UserRequest $request)
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'type' => $request->type,
            'status' => isset($request->status) ? 1 : 0,
            'password' => bcrypt($request->password),
        ]);

        alert()->success('Uğurlu', 'Əlavə olundu')
            ->showConfirmButton('Tamam', '#163A76');

        return redirect()->route('user.index');
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
        $post = User::find($id);
        return response()->json(['post' => $post], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, string $id)
    {
        $postUpdate = User::find($id);

        $request->validate([
            'email' => 'unique:users,email,' . $id,
        ], [
            'email.unique' => 'Bu e-poçt adresinə aid hesab artıq mövcuddur',
        ]);

        $postUpdate->name = $request->name;
        $postUpdate->type = $request->type;
        $postUpdate->email = $request->email;
        $postUpdate->status = isset($request->status) ? 1 : 0;

        if ($request->password) {
            $request->validate(
                [
                    "password" => "required|min:8|max:15",
                ],
                [
                    "password.required" => "Şifrə qeyd olunmalıdır",
                    "password.min" => "Şifrə 8-15 simvoldan ibarət olmalıdır",
                    "password.max" => "Şifrə 8-15 simvoldan ibarət olmalıdır",
                ]
            );
            $postUpdate->password = bcrypt($request->password);
        }

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
        $customer = User::find($id);
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
            $post = User::find($postID);
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
                $post = User::find($id);
                $post->status = 0;
                $post->save();
            }
        } else if ($request->val == 1) {
            foreach ($arr as $id) {
                $post = User::find($id);
                $post->status = 1;
                $post->save();
            }
        } else {
            foreach ($arr as $id) {
                $customer = User::find($id);
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
