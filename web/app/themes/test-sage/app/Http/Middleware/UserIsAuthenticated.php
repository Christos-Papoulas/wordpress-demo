<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserIsAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!is_user_logged_in()) {
            return response(['success' => false], 401);
        }

        return $next($request);
    }
}
