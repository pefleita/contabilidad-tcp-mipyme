<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!$request->user()) {
            return redirect('login');
        }

        $userRole = $request->user()->role;

        if ($role === 'admin' && $userRole !== 'admin') {
            return redirect('dashboard')->with('error', 'No tienes acceso a esta sección.');
        }

        if ($role === 'contador' && !in_array($userRole, ['admin', 'contador'])) {
            return redirect('dashboard')->with('error', 'No tienes acceso a esta sección.');
        }

        return $next($request);
    }
}
