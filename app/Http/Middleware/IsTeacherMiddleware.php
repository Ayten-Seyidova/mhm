<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsTeacherMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('teacher')->check()) {
            return redirect()->route('login');
        }

        $user = Auth::guard('teacher')->user();

        if ($user->status !== 1) {
            Auth::guard('teacher')->logout();
            return redirect()->route('login')->withErrors(['status' => 'Hesabınız deaktiv edilmişdir']);
        }

        if ($user->is_deleted == 1) {
            Auth::guard('teacher')->logout();
            return redirect()->route('login')->withErrors(['status' => 'Hesabınız silinmişdir']);
        }

        return $next($request);
    }

    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return route('login');
        }
    }
}
