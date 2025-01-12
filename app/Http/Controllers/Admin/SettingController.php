<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SettingRequest;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setting = Setting::find(1);

        return view('admin.pages.setting', compact('setting'));
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
    public function update(SettingRequest $request, string $id)
    {
        $settings = Setting::find($id);

        $settings->instagram = $request->instagram;
        $settings->facebook = $request->facebook;
        $settings->customer_service = $request->customer_service;
        $settings->security = str_replace(['<iframe', '&#39;'], ['<iframe allowfullscreen', "'"], $request->security);
        $settings->save();

        alert()->success('Uğurlu', 'Tənzimləmələr redaktə olundu')
            ->showConfirmButton('Tamam', '#3085d6');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    public function password()
    {
        return view('admin.pages.password');
    }

    public function changePassword(Request $request)
    {
        $user = Auth::guard('admin')->user();
        $userUpdate = User::find($user->id);

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
            $userUpdate->password = bcrypt($request->password);
            $userUpdate->save();

            alert()->success('Uğurlu', 'Şifrə uğurla dəyişdirildi')
                ->showConfirmButton('Tamam', '#163A76');

            Auth::logout();
            return redirect()->route('login');
        } else {
            return back();
        }
    }
}
