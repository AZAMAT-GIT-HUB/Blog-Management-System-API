<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Добавьте этот роут для исправления ошибки
Route::get('/login', function () {
    return response()->json([
        'message' => 'Unauthenticated'
    ], 401);
})->name('login');

Route::get('/reset-password/{token}', function ($token) {
    return response()->json(['token' => $token]); // или redirect на frontend
})->name('password.reset');
