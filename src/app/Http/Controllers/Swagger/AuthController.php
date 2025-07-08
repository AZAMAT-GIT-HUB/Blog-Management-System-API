<?php

namespace App\Http\Controllers\Swagger;

use App\Http\Controllers\Controller;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="A. Auth",
 *     description="Аутентификация и авторизация пользователей"
 * )
 */
class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/auth/register",
     *     tags={"Auth"},
     *     summary="Регистрация нового пользователя",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password", "password_confirmation"},
     *             @OA\Property(property="name", type="string", example="Azamat"),
     *             @OA\Property(property="email", type="string", example="azamat@example.com"),
     *             @OA\Property(property="password", type="string", example="password123"),
     *             @OA\Property(property="password_confirmation", type="string", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Пользователь успешно зарегистрирован",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Пользователь успешно зарегистрирован"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="user", ref="#/components/schemas/Author"),
     *                 @OA\Property(property="token", type="string", example="1|token123abc"),
     *                 @OA\Property(property="token_type", type="string", example="Bearer")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=500, description="Ошибка при регистрации")
     * )
     */
    public function register() {}

    /**
     * @OA\Post(
     *     path="/api/auth/login",
     *     tags={"Auth"},
     *     summary="Авторизация пользователя",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", example="azamat@example.com"),
     *             @OA\Property(property="password", type="string", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Пользователь успешно авторизован",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Пользователь успешно авторизован"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="user", ref="#/components/schemas/Author"),
     *                 @OA\Property(property="token", type="string", example="1|token123abc"),
     *                 @OA\Property(property="token_type", type="string", example="Bearer")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Неверные учетные данные"),
     *     @OA\Response(response=500, description="Ошибка при авторизации")
     * )
     */
    public function login() {}

    /**
     * @OA\Get(
     *     path="/api/auth/me",
     *     tags={"Auth"},
     *     summary="Получить текущего пользователя",
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Данные текущего пользователя",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="user", ref="#/components/schemas/Author")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Неавторизован")
     * )
     */
    public function me() {}

    /**
     * @OA\Post(
     *     path="/api/auth/logout",
     *     tags={"Auth"},
     *     summary="Выход из системы",
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Пользователь успешно вышел",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Пользователь успешно вышел из системы")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Неавторизован")
     * )
     */
    public function logout() {}

    /**
     * @OA\Post(
     *     path="/api/auth/forgot-password",
     *     tags={"Auth"},
     *     summary="Запрос сброса пароля",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", example="azamat@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ссылка отправлена",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Ссылка для сброса пароля отправлена на email")
     *         )
     *     ),
     *     @OA\Response(response=400, description="Не удалось отправить ссылку"),
     *     @OA\Response(response=500, description="Ошибка при отправке письма")
     * )
     */
    public function forgotPassword() {}

    /**
     * @OA\Post(
     *     path="/api/auth/reset-password",
     *     tags={"Auth"},
     *     summary="Сброс пароля",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password", "password_confirmation", "token"},
     *             @OA\Property(property="token", type="string", example="reset-token-123"),
     *             @OA\Property(property="email", type="string", example="azamat@example.com"),
     *             @OA\Property(property="password", type="string", example="newpassword123"),
     *             @OA\Property(property="password_confirmation", type="string", example="newpassword123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Пароль успешно сброшен",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Пароль успешно сброшен")
     *         )
     *     ),
     *     @OA\Response(response=400, description="Не удалось сбросить пароль"),
     *     @OA\Response(response=500, description="Ошибка при сбросе пароля")
     * )
     */
    public function resetPassword() {}
}
