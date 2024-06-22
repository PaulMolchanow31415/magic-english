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
        if ($role === Role::ADMIN && auth()->user()->role !== Role::ADMIN) {
            abort(Response::HTTP_FORBIDDEN);
        }
        if ($role === Role::USER && auth()->user()->role !== Role::USER) {
            abort(Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
