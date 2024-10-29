<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return route('login');
        }
    }

    protected function unauthenticated($request, array $guards)
    {
        // Return a JSON response directly for unauthenticated requests
        return response()->json([
            'success' => false,
            'message' => 'Unauthenticated, Please login.',
        ], 401);
    }

}
