<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Usage in web.php: ->middleware('role:admin')
     */
    public function handle(Request $request, Closure $next, ?string $role = null): Response
    {
        // 1. I-check kung ang user ay naka-login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // 2. I-check kung ang role sa database ($user->role) ay tugma sa required role ($role)
        if ($role !== null && $user->role !== $role) {
            // Kung hindi tugma (hal. student pumasok sa admin page), itapon sa 403 Forbidden
            abort(Response::HTTP_FORBIDDEN, 'Unauthorized access.');
        }

        return $next($request);
    }
}