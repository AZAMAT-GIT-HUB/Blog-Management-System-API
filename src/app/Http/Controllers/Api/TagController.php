<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Список всех тегов
     */
    public function index(Request $request): JsonResponse
    {
        $query = Tag::withCount('posts');

        // Фильтрация по активности
        if ($request->has('active')) {
            $query->where('is_active', $request->boolean('active'));
        }

        // Поиск
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'LIKE', "%{$search}%");
        }

        $tags = $query->orderBy('name')->get();

        return response()->json([
            'success' => true,
            'data' => TagResource::collection($tags)
        ]);
    }

    /**
     * Создание нового тега
     */
    public function store(StoreTagRequest $request): JsonResponse
    {
        try {
            $tag = Tag::create($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Тег успешно создан',
                'data' => new TagResource($tag)
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при создании тега',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Получение конкретного тега
     */
    public function show(Tag $tag): JsonResponse
    {
        $tag->loadCount('posts');

        return response()->json([
            'success' => true,
            'data' => new TagResource($tag)
        ]);
    }

    /**
     * Обновление тега
     */
    public function update(UpdateTagRequest $request, Tag $tag): JsonResponse
    {
        try {
            $tag->update($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Тег успешно обновлен',
                'data' => new TagResource($tag)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при обновлении тега',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Удаление тега
     */
    public function destroy(Tag $tag): JsonResponse
    {
        try {
            $tag->delete();

            return response()->json([
                'success' => true,
                'message' => 'Тег успешно удален'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при удалении тега',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
