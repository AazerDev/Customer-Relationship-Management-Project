<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RolePermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        if (!auth()->check()) {
            return $next($request);
        }
        if (empty($guards)) {
            return $next($request);
        }

        $user = $request->user();
        foreach ($guards as $guard) {
            if ($user->hasPermissionTo($guard)) {
                return $next($request);
            } else {
                return apiError('You do not have permission/role to access this resource', 403);
            }
        }

        return apiError('You do not have permission to access this resource', 403);
    }
}
