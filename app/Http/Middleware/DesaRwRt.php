<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DesaRwRt
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth()->user()->role == "Desa" || Auth()->user()->role == "RW" || Auth()->user()->role == "RT") {
            return $next($request);
        }

        else {
            $res['message'] = 'failed';
            $res['detail'] = 'API hanya bisa diakses oleh Desa, RW, dan RT';
            return response($res,406);
        }
    }
}
