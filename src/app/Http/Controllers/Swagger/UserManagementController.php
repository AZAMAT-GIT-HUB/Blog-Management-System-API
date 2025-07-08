<?php

namespace App\Http\Controllers\Swagger;

use App\Http\Controllers\Controller;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="B. User Management",
 *     description="Управление пользователями, ролями и разрешениями"
 * )
 */
class UserManagementController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/admin/users",
     *     tags={"User Management"},
     *     summary="Список всех пользователей (только для админов)",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(name="role", in="query", required=false, @OA\Schema(type="string")),
     *     @OA\Parameter(name="search", in="query", required=false, @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="Успешно",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Author")),
     *             @OA\Property(property="pagination", type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="total_pages", type="integer", example=5),
     *                 @OA\Property(property="total", type="integer", example=100)
     *             )
     *         )
     *     )
     * )
     */
    public function index() {}


    /**
     * @OA\Post(
     *     path="/api/admin/users/{id}/assign-role",
     *     tags={"User Management"},
     *     summary="Назначение роли пользователю",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"role"},
     *             @OA\Property(property="role", type="string", example="editor")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Роль назначена успешно"),
     *     @OA\Response(response=500, description="Ошибка при назначении роли")
     * )
     */
    public function assignRole() {}

    /**
     * @OA\Get(
     *     path="/api/admin/roles",
     *     tags={"User Management"},
     *     summary="Получение всех ролей",
     *     security={{"sanctum": {}}},
     *     @OA\Response(response=200, description="Список ролей",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="admin"),
     *                 @OA\Property(property="permissions", type="array", @OA\Items(type="string")),
     *                 @OA\Property(property="users_count", type="integer", example=3)
     *             ))
     *         )
     *     )
     * )
     */
    public function getRoles() {}

    /**
     * @OA\Get(
     *     path="/api/admin/permissions",
     *     tags={"User Management"},
     *     summary="Получение всех разрешений",
     *     security={{"sanctum": {}}},
     *     @OA\Response(response=200, description="Список разрешений",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function getPermissions() {}

    /**
     * @OA\Get(
     *     path="/api/auth/profile",
     *     tags={"User Management"},
     *     summary="Информация о текущем пользователе",
     *     security={{"sanctum": {}}},
     *     @OA\Response(response=200, description="Данные пользователя",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="user", ref="#/components/schemas/Author"),
     *                 @OA\Property(property="abilities", type="object",
     *                     @OA\Property(property="can_manage_posts", type="boolean", example=true),
     *                     @OA\Property(property="can_publish_posts", type="boolean", example=false),
     *                     @OA\Property(property="can_manage_categories", type="boolean", example=true),
     *                     @OA\Property(property="can_manage_users", type="boolean", example=false),
     *                     @OA\Property(property="can_access_admin", type="boolean", example=true)
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function profile() {}
}
