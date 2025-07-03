<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;

class TrimStrings
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
        // Menghapus spasi yang tidak perlu dari input
        $request->merge(array_map('trim', $request->all()));

        return $next($request);
    }
}
