<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Check against both user role column and Spatie role for robustness
        if ($user->role !== $role && !$user->hasRole($role)) {
            abort(403, 'Akses ditolak. Anda tidak memiliki wewenang untuk membuka halaman ini.');
        }

        return $next($request);
    }
}
