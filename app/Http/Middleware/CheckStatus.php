<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckStatus
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->status !== 'approved') {
            Auth::logout();
            return redirect('/login')->withErrors(['error' => 'Akun Anda belum disetujui oleh Admin.']);
        }

        return $next($request);
    }
}
