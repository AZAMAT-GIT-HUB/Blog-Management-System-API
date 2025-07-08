<?php
// app/Http/Middleware/CheckPermission.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Необходима аутентификация'
            ], 401);
        }

        if (!auth()->user()->hasPermissionTo($permission)) {
            return response()->json([
                'success' => false,
                'message' => 'Недостаточно прав для выполнения этого действия',
                'required_permission' => $permission
            ], 403);
        }

        return $next($request);
    }
}
