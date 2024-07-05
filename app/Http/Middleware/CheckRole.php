<?php

namespace App\Http\Middleware;

use Closure;
use App\Role;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole {
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response {
        if ($role === Role::ADMIN->name && !user()->hasRole(Role::ADMIN)) {
            forbidden();
        }
        if ($role === Role::USER->name && !user()->hasRole(Role::USER)) {
            forbidden();
        }

        return $next($request);
    }
}
