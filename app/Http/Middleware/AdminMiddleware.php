<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            Log::info('Admin user:', [Auth::user()]); // Catat informasi user admin ke log
            return $next($request);  // Lanjutkan jika user adalah admin
        }
        
        // Redirect jika bukan admin
        return redirect('/login')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
    }
}
