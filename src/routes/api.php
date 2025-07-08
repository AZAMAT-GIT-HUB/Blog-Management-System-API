<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\UserManagementController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/
// Публичные маршруты (без аутентификации)
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
});

// Публичные маршруты для блога (чтение)
Route::prefix('blog')->group(function () {
    Route::get('/posts', [PostController::class, 'index']);
    Route::get('/posts/{post}', [PostController::class, 'show']);
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{category}', [CategoryController::class, 'show']);
    Route::get('/tags', [TagController::class, 'index']);
    Route::get('/tags/{tag}', [TagController::class, 'show']);
});

// Защищенные маршруты (требуют аутентификации)
Route::middleware('auth:sanctum')->group(function () {

    // Аутентификация
    Route::prefix('auth')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/profile', [UserManagementController::class, 'profile']);
    });

    // Управление постами (с проверкой разрешений)
    Route::prefix('posts')->group(function () {
        Route::post('/', [PostController::class, 'store'])->middleware('permission:create_posts');
        Route::put('/{post}', [PostController::class, 'update'])->middleware('permission:edit_posts');
        Route::delete('/{post}', [PostController::class, 'destroy'])->middleware('permission:manage_posts');
    });

    // Управление категориями (только для редакторов и админов)
    Route::middleware('permission:manage_categories')->prefix('categories')->group(function () {
        Route::post('/', [CategoryController::class, 'store']);
        Route::put('/{category}', [CategoryController::class, 'update']);
        Route::delete('/{category}', [CategoryController::class, 'destroy']);
    });

    // Управление тегами (только для редакторов и админов)
    Route::middleware('permission:manage_tags')->prefix('tags')->group(function () {
        Route::post('/', [TagController::class, 'store']);
        Route::put('/{tag}', [TagController::class, 'update']);
        Route::delete('/{tag}', [TagController::class, 'destroy']);
    });

    // Админские маршруты (только для администраторов)
    Route::middleware('permission:manage_users')->prefix('admin')->group(function () {
        Route::get('/users', [UserManagementController::class, 'index']);
        Route::post('/users/{user}/assign-role', [UserManagementController::class, 'assignRole']);
        Route::get('/roles', [UserManagementController::class, 'getRoles']);
        Route::get('/permissions', [UserManagementController::class, 'getPermissions']);
    });
});

// Базовый маршрут для проверки API
Route::get('/test', function () {
    return response()->json([
        'success' => true,
        'message' => 'API работает корректно',
        'timestamp' => now()
    ]);
});
