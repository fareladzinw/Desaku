<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Desa
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
        if (Auth()->user()->role == "Desa") {
            return $next($request);
        }

        else {
            $res['message'] = 'failed';
            $res['detail'] = 'API hanya bisa diakses oleh Desa';
            return response($res,406);
        }
    }
}
