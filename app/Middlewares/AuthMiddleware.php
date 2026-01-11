<?php

namespace App\Middlewares;

use Belur\Auth\Auth;
use Belur\Http\Middleware;
use Belur\Http\Request;
use Belur\Http\Response;
use Closure;

class AuthMiddleware implements Middleware {
    public function handle(Request $request, Closure $next): Response {
        if (Auth::isGuest()) {
            return redirect('/login');
        }

        return $next($request);
    }
}