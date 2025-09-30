<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $userRole = auth()->user()->role;
        
        // Debug: Log the user role and required roles
        \Log::info('RoleMiddleware Debug', [
            'user_role' => $userRole,
            'required_roles' => $roles,
            'user_id' => auth()->id(),
            'route' => $request->route()->getName()
        ]);
        
        if (in_array($userRole, $roles)) {
            return $next($request);
        }

        abort(403, "Unauthorized access. User role: {$userRole}, Required roles: " . implode(', ', $roles));
    }
}
