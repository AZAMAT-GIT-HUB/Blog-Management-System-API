<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Список всех категорий
     */
    public function index(Request $request): JsonResponse
    {
        $query = Category::withCount('posts');

        // Фильтрация по активности
        if ($request->has('active')) {
            $query->where('is_active', $request->boolean('active'));
        }

        // Поиск
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'LIKE', "%{$search}%");
        }

        $categories = $query->orderBy('name')->get();

        return response()->json([
            'success' => true,
            'data' => CategoryResource::collection($categories)
        ]);
    }

    /**
     * Создание новой категории
     */
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        try {
            $category = Category::create($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Категория успешно создана',
                'data' => new CategoryResource($category)
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при создании категории',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Получение конкретной категории
     */
    public function show(Category $category): JsonResponse
    {
        $category->loadCount('posts');

        return response()->json([
            'success' => true,
            'data' => new CategoryResource($category)
        ]);
    }

    /**
     * Обновление категории
     */
    public function update(UpdateCategoryRequest $request, Category $category): JsonResponse
    {
        try {
            $category->update($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Категория успешно обновлена',
                'data' => new CategoryResource($category)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при обновлении категории',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Удаление категории
     */
    public function destroy(Category $category): JsonResponse
    {
        try {
            $category->delete();

            return response()->json([
                'success' => true,
                'message' => 'Категория успешно удалена'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при удалении категории',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
