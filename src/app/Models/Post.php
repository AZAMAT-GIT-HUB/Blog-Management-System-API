<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Traits\HasFeaturedImage;

class Post extends Model
{
    use HasFactory, HasFeaturedImage;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'status',
        'published_at',
        'author_id',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    /**
     * Связь с автором (пользователем)
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Связь many-to-many с категориями
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class)->withTimestamps();
    }

    /**
     * Связь many-to-many с тегами
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    /**
     * Автоматическое создание slug из title
     */
    public function setTitleAttribute($value): void
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = \Str::slug($value);
    }

    /**
     * Автоматическая установка published_at при публикации
     */
    public function setStatusAttribute($value): void
    {
        $this->attributes['status'] = $value;

        if ($value === 'published' && !$this->published_at) {
            $this->attributes['published_at'] = now();
        }
    }

    /**
     * Scope для опубликованных постов
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where('published_at', '<=', now());
    }

    /**
     * Scope для черновиков
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope для постов определенного автора
     */
    public function scopeByAuthor($query, $authorId)
    {
        return $query->where('author_id', $authorId);
    }

    /**
     * Увеличение счетчика просмотров
     */
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }
}
