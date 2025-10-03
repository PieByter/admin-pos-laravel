<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        // Misal role disimpan di session
        if (session('role') !== $role) {
            return redirect()->route('forbidden');
        }
        return $next($request);
    }
}
