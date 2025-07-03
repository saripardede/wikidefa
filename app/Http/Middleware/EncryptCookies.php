<?php

namespace App\Http\Middleware;

use Closure;

class EncryptCookies
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Contoh: Menambahkan logika untuk enkripsi cookies di sini
        return $next($request);
    }
}
