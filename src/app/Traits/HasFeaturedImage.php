<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait HasFeaturedImage
{
    /**
     * Загрузка и сохранение изображения
     */
    public function uploadFeaturedImage(UploadedFile $file, string $folder = 'posts'): string
    {
        // Генерация уникального имени файла
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        // Путь для сохранения
        $path = "images/{$folder}";

        // Создание директории если не существует
        if (!Storage::disk('public')->exists($path)) {
            Storage::disk('public')->makeDirectory($path);
        }

        // Сохранение файла
        $fullPath = $file->storeAs($path, $filename, 'public');

        return $fullPath;
    }

    /**
     * Получение URL изображения
     */
    public function getFeaturedImageUrlAttribute(): ?string
    {
        if (!$this->featured_image) {
            return null;
        }

        return Storage::disk('public')->url($this->featured_image);
    }

    /**
     * Получение URL thumbnail (возвращаем основное изображение)
     */
    public function getThumbnailUrlAttribute(): ?string
    {
        return $this->featured_image_url;
    }

    /**
     * Удаление изображения
     */
    public function deleteFeaturedImage(): void
    {
        if ($this->featured_image && Storage::disk('public')->exists($this->featured_image)) {
            Storage::disk('public')->delete($this->featured_image);
        }
    }
}
