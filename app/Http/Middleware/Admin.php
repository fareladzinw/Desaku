<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class Admin
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
        if (Auth()->user()->role == "Admin") {
            return $next($request);
        }

        else {
            $res['message'] = 'failed';
            $res['detail'] = 'API hanya bisa diakses oleh admin';
            return response($res,406);
        }
    }
}
