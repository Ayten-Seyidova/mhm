<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $email = $request->email;
        $password = $request->password;
        $remember = $request->remember;
        !is_null($remember) ? $remember = true : $remember = false;

        $user = User::where('email', $email)->where('is_deleted', 0)->where('status', 1)->first();
        if ($user && Hash::check($password, $user->password) && $user->type == 'admin') {
            Auth::guard('teacher')->logout();
            Auth::guard('admin')->login($user, $remember);
            return redirect()->route('customer.index');
        } elseif ($user && Hash::check($password, $user->password) && $user->type == 'teacher') {
            Auth::guard('admin')->logout();
            Auth::guard('teacher')->login($user, $remember);
            return redirect()->route('teacher.index');
        } else {
            alert()->error('Xəta', 'E-poçt və ya şifrə düzgün daxil edilməmişdir')->showConfirmButton('Tamam', '#163A76');
            return redirect()->route('login');
        }
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        Auth::guard('teacher')->logout();
        return redirect()->route('login');
    }
}
