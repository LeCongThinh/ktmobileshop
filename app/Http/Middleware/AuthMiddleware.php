<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware
{
    //auth.users
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->role == 2) {
            return redirect('/');
        }
        return $next($request);
    }
}
