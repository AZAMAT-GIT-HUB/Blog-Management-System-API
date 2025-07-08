<?php


namespace App\Http\Controllers\Swagger\Schemas;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *      schema="Author",
 *      type="object",
 *      @OA\Property(property="id", type="integer", example=1),
 *      @OA\Property(property="name", type="string", example="Azamat"),
 *      @OA\Property(property="email", type="string", example="azamat@example.com"),
 *      @OA\Property(property="email_verified_at", type="string", format="date-time"),
 *      @OA\Property(property="roles", type="array", @OA\Items(type="string")),
 *      @OA\Property(property="permissions", type="array", @OA\Items(type="string")),
 *      @OA\Property(property="created_at", type="string", format="date-time"),
 *      @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *     schema="Category",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Технологии"),
 *     @OA\Property(property="slug", type="string", example="texnologii"),
 *     @OA\Property(property="description", type="string", example="Описание категории"),
 *     @OA\Property(property="color", type="string", example="#3B82F6"),
 *     @OA\Property(property="is_active", type="boolean", example=true),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *     schema="Tag",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="PHP"),
 *     @OA\Property(property="slug", type="string", example="php"),
 *     @OA\Property(property="color", type="string", example="#777BB4"),
 *     @OA\Property(property="is_active", type="boolean", example=true),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *     schema="Post",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Пост с изображением от Пользователя"),
 *     @OA\Property(property="slug", type="string", example="Post-s-izobrazeniem-ot-polzovatelya"),
 *     @OA\Property(property="excerpt", type="string", example="Краткое описание поста"),
 *     @OA\Property(property="content", type="string", example="Контент поста"),
 *     @OA\Property(property="featured_image", type="string", format="url", example="http://localhost/storage/images/posts/example.jpg"),
 *     @OA\Property(property="thumbnail", type="string", format="url", example="http://localhost/storage/images/posts/example.jpg"),
 *     @OA\Property(property="status", type="string", example="published"),
 *     @OA\Property(property="published_at", type="string", format="date-time"),
 *     @OA\Property(property="views_count", type="integer", example=0),
 *
 *     @OA\Property(property="author", ref="#/components/schemas/Author"),
 *     @OA\Property(property="categories", type="array", @OA\Items(ref="#/components/schemas/Category")),
 *     @OA\Property(property="tags", type="array", @OA\Items(ref="#/components/schemas/Tag")),
 *
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class AllSchemas
{
}
