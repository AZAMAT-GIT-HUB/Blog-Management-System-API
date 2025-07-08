<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserManagementController extends Controller
{
    /**
     * Список всех пользователей (только для админов)
     */
    public function index(Request $request): JsonResponse
    {
        $query = User::with('roles', 'permissions');

        // Фильтрация по роли
        if ($request->has('role')) {
            $query->role($request->role);
        }

        // Поиск по имени или email
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        $users = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => UserResource::collection($users),
            'pagination' => [
                'current_page' => $users->currentPage(),
                'total_pages' => $users->lastPage(),
                'total' => $users->total(),
            ]
        ]);
    }

    /**
     * Назначение роли пользователю
     */
    public function assignRole(Request $request, User $user): JsonResponse
    {
        $request->validate([
            'role' => 'required|exists:roles,name'
        ]);

        try {
            $user->syncRoles([$request->role]);

            return response()->json([
                'success' => true,
                'message' => 'Роль успешно назначена',
                'data' => new UserResource($user)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при назначении роли',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Получение всех ролей
     */
    public function getRoles(): JsonResponse
    {
        $roles = Role::with('permissions')->get();

        return response()->json([
            'success' => true,
            'data' => $roles->map(function ($role) {
                return [
                    'id' => $role->id,
                    'name' => $role->name,
                    'permissions' => $role->permissions->pluck('name'),
                    'users_count' => $role->users()->count(),
                ];
            })
        ]);
    }

    /**
     * Получение всех разрешений
     */
    public function getPermissions(): JsonResponse
    {
        $permissions = Permission::all()->groupBy(function ($permission) {
            return explode('_', $permission->name)[1] ?? 'other';
        });

        return response()->json([
            'success' => true,
            'data' => $permissions
        ]);
    }

    /**
     * Информация о текущем пользователе с ролями и разрешениями
     */
    public function profile(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->load('roles', 'permissions');

        return response()->json([
            'success' => true,
            'data' => [
                'user' => new UserResource($user),
                'abilities' => [
                    'can_manage_posts' => $user->hasPermissionTo('manage_posts'),
                    'can_publish_posts' => $user->hasPermissionTo('publish_posts'),
                    'can_manage_categories' => $user->hasPermissionTo('manage_categories'),
                    'can_manage_users' => $user->hasPermissionTo('manage_users'),
                    'can_access_admin' => $user->hasPermissionTo('access_admin_panel'),
                ]
            ]
        ]);
    }
}
