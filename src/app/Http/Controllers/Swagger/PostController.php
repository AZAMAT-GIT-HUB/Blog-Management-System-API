<?php

namespace App\Http\Controllers\Swagger;

use App\Http\Controllers\Controller;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="E. Posts",
 *     description="Управление постами блога"
 * )
 */
class PostController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/blog/posts",
     *     tags={"Posts"},
     *     summary="Получить список постов",
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Фильтрация по статусу (draft/published)",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="author_id",
     *         in="query",
     *         description="Фильтрация по ID автора",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="category",
     *         in="query",
     *         description="Фильтрация по slug категории",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="tag",
     *         in="query",
     *         description="Фильтрация по slug тега",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Поиск по заголовку и содержимому",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="sort_by",
     *         in="query",
     *         description="Сортировка (например: created_at)",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="sort_direction",
     *         in="query",
     *         description="Направление сортировки (asc/desc)",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Количество элементов на странице",
     *         required=false,
     *         @OA\Schema(type="integer", default=15)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешно",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Post")
     *             )
     *         )
     *     )
     * )
     */
    public function index() {}


    /**
     * @OA\Post(
     *     path="/api/posts",
     *     tags={"Posts"},
     *     summary="Создание нового поста",
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"title", "content", "status"},
     *                 type="object",
     *                 @OA\Property(property="title", type="string", example="Заголовок поста"),
     *                 @OA\Property(property="content", type="string", example="Содержимое поста"),
     *                 @OA\Property(property="excerpt", type="string", example="Краткое описание"),
     *                 @OA\Property(property="status", type="string", example="draft"),
     *                 @OA\Property(
     *                     property="categories",
     *                     type="array",
     *                     @OA\Items(type="integer")
     *                 ),
     *                 @OA\Property(
     *                     property="tags",
     *                     type="array",
     *                     @OA\Items(type="integer")
     *                 ),
     *                 @OA\Property(
     *                     property="featured_image",
     *                     type="string",
     *                     format="binary",
     *                     description="Изображение обложки поста"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Пост создан",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Пост успешно создан"),
     *             @OA\Property(property="data", ref="#/components/schemas/Post")
     *         )
     *     )
     * )
     */
    public function store() {}



    /**
     * @OA\Get(
     *     path="/api/blog/posts/{post}",
     *     tags={"Posts"},
     *     summary="Получить конкретный пост",
     *     description="Возвращает один пост по ID",
     *     @OA\Parameter(
     *         name="post",
     *         in="path",
     *         required=true,
     *         description="ID поста",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешно",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/Post")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Пост не найден",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Пост не найден")
     *         )
     *     )
     * )
     */
    public function show() {}


    /**
     * @OA\Put(
     *     path="/api/posts/{post}",
     *     tags={"Posts"},
     *     summary="Обновить пост",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="post",
     *         in="path",
     *         required=true,
     *         description="ID поста",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="title", type="string", example="Новый заголовок"),
     *                 @OA\Property(property="content", type="string", example="Обновлённый контент"),
     *                 @OA\Property(property="excerpt", type="string", example="Новый краткий текст"),
     *                 @OA\Property(property="status", type="string", example="published"),
     *                 @OA\Property(property="categories", type="array", @OA\Items(type="integer"), description="ID категорий", example={1,2}),
     *                 @OA\Property(property="tags", type="array", @OA\Items(type="integer"), description="ID тегов", example={3,4}),
     *                 @OA\Property(
     *                     property="featured_image",
     *                     type="string",
     *                     format="binary",
     *                     description="Новое изображение (обложка)"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Пост успешно обновлен",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Пост успешно обновлен"),
     *             @OA\Property(property="data", ref="#/components/schemas/Post")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Нет прав на редактирование",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="У вас нет прав для редактирования этого поста")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Ошибка при обновлении поста",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Ошибка при обновлении поста"),
     *             @OA\Property(property="error", type="string", example="SQLSTATE[...]: Error message here")
     *         )
     *     )
     * )
     */
    public function update() {}



    /**
     * @OA\Delete(
     *     path="/api/posts/{post}",
     *     tags={"Posts"},
     *     summary="Удалить пост",
     *     description="Удаляет указанный пост по ID, включая изображение",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="post",
     *         in="path",
     *         required=true,
     *         description="ID поста",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Пост удалён успешно",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Пост успешно удален")
     *         )
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Неавторизован. Пользователь должен авторизоваться для выполнения этого действия.",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Unauthenticated."),
     *              @OA\Property(property="note", type="string", example="Необходимо авторизоваться, используя токен доступа.")
     *          )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Нет прав на удаление",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="У вас нет прав для удаления этого поста")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Ошибка при удалении поста",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Ошибка при удалении поста"),
     *             @OA\Property(property="error", type="string", example="SQLSTATE[...]: Database error")
     *         )
     *     )
     * )
     */
    public function destroy() {}

}
