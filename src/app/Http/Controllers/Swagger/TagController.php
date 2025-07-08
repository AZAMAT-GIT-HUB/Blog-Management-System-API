<?php

namespace App\Http\Controllers\Swagger;

use App\Http\Controllers\Controller;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="D. Tags",
 *     description="Управление тегами"
 * )
 */
class TagController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/blog/tags",
     *     tags={"Tags"},
     *     summary="Список всех тегов",
     *     @OA\Parameter(name="active", in="query", description="Фильтр по активности", @OA\Schema(type="boolean")),
     *     @OA\Parameter(name="search", in="query", description="Поиск по названию", @OA\Schema(type="string")),
     *     @OA\Response(
     *         response=200,
     *         description="Успешно",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Tag"))
     *         )
     *     )
     * )
     */
    public function index() {}

    /**
     * @OA\Post(
     *     path="/api/tags",
     *     tags={"Tags"},
     *     summary="Создание нового тега",
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "slug"},
     *             @OA\Property(property="name", type="string", example="PHP"),
     *             @OA\Property(property="slug", type="string", example="php"),
     *             @OA\Property(property="color", type="string", example="#777BB4"),
     *             @OA\Property(property="is_active", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Тег создан",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Тег успешно создан"),
     *             @OA\Property(property="data", ref="#/components/schemas/Tag")
     *         )
     *     )
     * )
     */
    public function store() {}

    /**
     * @OA\Get(
     *     path="/api/blog/tags/{tag}",
     *     tags={"Tags"},
     *     summary="Получить тег по ID",
     *     @OA\Parameter(
     *         name="tag",
     *         in="path",
     *         required=true,
     *         description="ID тега",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешно",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/Tag")
     *         )
     *     )
     * )
     */
    public function show() {}

    /**
     * @OA\Put(
     *     path="/api/tags/{tag}",
     *     tags={"Tags"},
     *     summary="Обновить тег",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="tag",
     *         in="path",
     *         required=true,
     *         description="ID тега",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="PHP"),
     *             @OA\Property(property="slug", type="string", example="php"),
     *             @OA\Property(property="color", type="string", example="#777BB4"),
     *             @OA\Property(property="is_active", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Тег обновлён",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Тег успешно обновлен"),
     *             @OA\Property(property="data", ref="#/components/schemas/Tag")
     *         )
     *     )
     * )
     */
    public function update() {}

    /**
     * @OA\Delete(
     *     path="/api/tags/{tag}",
     *     tags={"Tags"},
     *     summary="Удалить тег",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="tag",
     *         in="path",
     *         required=true,
     *         description="ID тега",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Тег удалён",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Тег успешно удален")
     *         )
     *     )
     * )
     */
    public function destroy() {}
}
