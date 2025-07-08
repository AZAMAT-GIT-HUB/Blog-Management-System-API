<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Связь с постами (автор)
     */
    public function posts()
    {
        return $this->hasMany(Post::class, 'author_id');
    }

    /**
     * Проверка является ли пользователь администратором
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Проверка является ли пользователь редактором
     */
    public function isEditor(): bool
    {
        return $this->hasRole(['admin', 'editor']);
    }

    /**
     * Проверка является ли пользователь автором
     */
    public function isAuthor(): bool
    {
        return $this->hasRole(['admin', 'editor', 'author']);
    }

    /**
     * Может ли пользователь редактировать пост
     */
    public function canEditPost($post): bool
    {
        if ($this->hasPermissionTo('manage_posts')) {
            return true; // Админ/Редактор может редактировать любые посты
        }

        if ($this->hasPermissionTo('edit_posts') && $post->author_id === $this->id) {
            return true; // Автор может редактировать свои посты
        }

        return false;
    }

    /**
     * Может ли пользователь удалить пост
     */
    public function canDeletePost($post): bool
    {
        if ($this->hasPermissionTo('manage_posts')) {
            return true; // Админ/Редактор может удалять любые посты
        }

        if ($this->hasPermissionTo('delete_posts') && $post->author_id === $this->id) {
            return true; // Автор может удалять свои посты
        }

        return false;
    }
}
