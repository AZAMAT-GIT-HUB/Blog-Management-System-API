<?php

namespace App\Http\Controllers\Swagger;

use App\Http\Controllers\Controller;
/**
 * @OA\Tag(
 *     name="C. Categories",
 *     description="Управление категорами"
 * )
 */
class CategoryController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/blog/categories",
     *     tags={"Categories"},
     *     summary="Список всех категорий",
     *     @OA\Parameter(name="active", in="query", description="Фильтр по активности", @OA\Schema(type="boolean")),
     *     @OA\Parameter(name="search", in="query", description="Поиск по названию", @OA\Schema(type="string")),
     *     @OA\Response(
     *         response=200,
     *         description="Успешно",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Category"))
     *         )
     *     )
     * )
     */
    public function index() {}


    /**
     * @OA\Post(
     *     path="/api/categories",
     *     tags={"Categories"},
     *     summary="Создание новой категории",
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "slug"},
     *             @OA\Property(property="name", type="string", example="Технологии"),
     *             @OA\Property(property="slug", type="string", example="texnologii"),
     *             @OA\Property(property="description", type="string", example="Описание категории"),
     *             @OA\Property(property="color", type="string", example="#3B82F6"),
     *             @OA\Property(property="is_active", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Категория создана",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Категория успешно создана"),
     *             @OA\Property(property="data", ref="#/components/schemas/Category")
     *         )
     *     )
     * )
     */
    public function store() {}


    /**
     * @OA\Get(
     *     path="/api/blog/categories/{category}",
     *     tags={"Categories"},
     *     summary="Получить категорию по ID",
     *     @OA\Parameter(
     *         name="category",
     *         in="path",
     *         required=true,
     *         description="ID категории",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешно",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/Category")
     *         )
     *     )
     * )
     */
    public function show() {}


    /**
     * @OA\Put(
     *     path="/api/categories/{category}",
     *     tags={"Categories"},
     *     summary="Обновить категорию",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="category",
     *         in="path",
     *         required=true,
     *         description="ID категории",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Технологии"),
     *             @OA\Property(property="slug", type="string", example="texnologii"),
     *             @OA\Property(property="description", type="string", example="Описание категории"),
     *             @OA\Property(property="color", type="string", example="#3B82F6"),
     *             @OA\Property(property="is_active", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешно обновлено",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Категория успешно обновлена"),
     *             @OA\Property(property="data", ref="#/components/schemas/Category")
     *         )
     *     )
     * )
     */
    public function update() {}


    /**
     * @OA\Delete(
     *     path="/api/categories/{category}",
     *     tags={"Categories"},
     *     summary="Удалить категорию",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="category",
     *         in="path",
     *         required=true,
     *         description="ID категории",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Категория удалена",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Категория успешно удалена")
     *         )
     *     )
     * )
     */
    public function destroy() {}

}
