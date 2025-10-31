<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // Assuming 'admin' is authenticated through sanctum
        $user = Auth::guard('sanctum')->user();

        // You can check if the authenticated user is an admin
        if ($user && $user->is_admin) {
            return $next($request);
        }

        return response()->json(['message' => 'Unauthorized'], 403);
    }
}
