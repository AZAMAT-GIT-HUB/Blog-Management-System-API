<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Http\Resources\PostCollection;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Список всех постов с пагинацией и фильтрацией
     */
    public function index(Request $request): PostCollection
    {
        $query = Post::with(['author', 'categories', 'tags']);

        // Получаем статус из запроса, если указан
        $requestedStatus = $request->get('status');

        // Только если статус не указан, применяем ограничения по правам
        if ($requestedStatus) {
            $query->where('status', $requestedStatus);
        }

        // Фильтрация по автору
        if ($request->has('author_id')) {
            $query->where('author_id', $request->author_id);
        }

        // Фильтрация по категории
        if ($request->has('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Фильтрация по тегу
        if ($request->has('tag')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('slug', $request->tag);
            });
        }

        // Поиск по заголовку и содержимому
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('content', 'LIKE', "%{$search}%");
            });
        }

        // Сортировка
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);

        $posts = $query->paginate($request->get('per_page', 15));

        return new PostCollection($posts);
    }


    /**
     * Создание нового поста
     */
    public function store(StorePostRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $data['author_id'] = auth()->id();

            // Обработка загрузки изображения
            if ($request->hasFile('featured_image')) {
                $post = new Post();
                $data['featured_image'] = $post->uploadFeaturedImage($request->file('featured_image'));
            }

            $post = Post::create($data);

            // Привязка категорий
            if (!empty($data['categories'])) {
                $post->categories()->sync($data['categories']);
            }

            // Привязка тегов
            if (!empty($data['tags'])) {
                $post->tags()->sync($data['tags']);
            }

            // Подгрузка связей.
            $post->load(['author', 'categories', 'tags']);

            return response()->json([
                'success' => true,
                'message' => 'Пост успешно создан',
                'data' => new PostResource($post)
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при создании поста',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Получение конкретного поста
     */
    public function show(Post $post): JsonResponse
    {
        $post->load(['author', 'categories', 'tags']);
        $post->incrementViews();

        return response()->json([
            'success' => true,
            'data' => new PostResource($post)
        ]);
    }

    /**
     * Обновление поста
     */
    public function update(UpdatePostRequest $request, Post $post): JsonResponse
    {
        // Проверяем права на редактирование конкретного поста
        if (!auth()->user()->canEditPost($post)) {
            return response()->json([
                'success' => false,
                'message' => 'У вас нет прав для редактирования этого поста'
            ], 403);
        }

        // Если пользователь не может публиковать, принудительно ставим draft
        if ($request->status === 'published' && !auth()->user()->hasPermissionTo('publish_posts')) {
            $request->merge(['status' => 'draft']);
        }

        try {
            $data = $request->validated();

            // Обработка загрузки нового изображения
            if ($request->hasFile('featured_image')) {
                // Удаление старого изображения
                $post->deleteFeaturedImage();

                // Загрузка нового
                $data['featured_image'] = $post->uploadFeaturedImage($request->file('featured_image'));
            }

            $post->update($data);

            // Обновление категорий
            if (array_key_exists('categories', $data)) {
                $post->categories()->sync($data['categories'] ?? []);
            }

            // Обновление тегов
            if (array_key_exists('tags', $data)) {
                $post->tags()->sync($data['tags'] ?? []);
            }

            $post->load(['author', 'categories', 'tags']);

            return response()->json([
                'success' => true,
                'message' => 'Пост успешно обновлен',
                'data' => new PostResource($post)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при обновлении поста',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Удаление поста
     */
    public function destroy(Post $post): JsonResponse
    {
        if (!auth()->user()->canDeletePost($post)) {
            return response()->json([
                'success' => false,
                'message' => 'У вас нет прав для удаления этого поста'
            ], 403);
        }

        try {
            // Удаление изображения
            $post->deleteFeaturedImage();

            // Удаление поста
            $post->delete();

            return response()->json([
                'success' => true,
                'message' => 'Пост успешно удален'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при удалении поста',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
