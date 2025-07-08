<?php

namespace App\Http\Controllers\Swagger;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *     title="Blog Management System API",
 *     version="1.0.0",
 *     description="RESTful API для системы управления блогом с аутентификацией и системой ролей",
 *     @OA\Contact(
 *         email="u.azamat.1997@gmail.com"
 *     )
 * )
 *
 * @OA\Server(
 *     url="http://localhost:8080",
 *     description="Основной сервер API"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Laravel Sanctum token authentication"
 * )
 *
 * @OA\Tag(name="Z. Test", description="Тестовые эндпоинты")
 *
 */
class MainController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/test",
     *     tags={"Test"},
     *     summary="Тестовый эндпоинт",
     *     description="Проверка работы API",
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="API работает корректно"),
     *             @OA\Property(property="timestamp", type="string", format="date-time")
     *         )
     *     )
     * )
     */
    public function test()
    {
        return response()->json([
            'success' => true,
            'message' => 'API работает корректно',
            'timestamp' => now()
        ]);
    }
}
