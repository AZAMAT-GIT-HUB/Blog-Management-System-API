<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Необходима аутентификация'
            ], 401);
        }

        if (!auth()->user()->hasRole($role)) {
            return response()->json([
                'success' => false,
                'message' => 'Недостаточно прав для выполнения этого действия',
                'required_role' => $role
            ], 403);
        }

        return $next($request);
    }
}
