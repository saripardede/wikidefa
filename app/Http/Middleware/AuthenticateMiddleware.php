<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticateMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect('/login')->withErrors(['error' => 'Silakan login terlebih dahulu.']);
        }
        return $next($request);
    }

    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            // Cek apakah rute admin atau bukan
            if ($request->is('admin/*')) {
                return route('admin.login'); // Pastikan rute ini ada
            }

            return route('login');
        }
    }
}
