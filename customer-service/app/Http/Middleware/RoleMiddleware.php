<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        if (! auth()->user()->is_active) {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'email' => 'Akun Anda nonaktif. Silakan hubungi administrator.',
            ]);
        }

        $userRole = auth()->user()->role;
        
        if (in_array($userRole, $roles)) {
            return $next($request);
        }

        abort(403, "Unauthorized access. User role: {$userRole}, Required roles: " . implode(', ', $roles));
    }
}
