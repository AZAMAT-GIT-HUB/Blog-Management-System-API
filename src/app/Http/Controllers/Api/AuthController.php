<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Регистрация нового пользователя
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Назначаем роль "reader" по умолчанию
            $user->assignRole('reader');

            // Загружаем роли для ответа
            $user->load('roles');

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Пользователь успешно зарегистрирован',
                'data' => [
                    'user' => new UserResource($user),
                    'token' => $token,
                    'token_type' => 'Bearer',
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при регистрации пользователя',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Авторизация пользователя
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            if (!Auth::attempt($request->only(['email', 'password']))) {
                return response()->json([
                    'success' => false,
                    'message' => 'Неверные учетные данные',
                ], 401);
            }

            $user = User::where('email', $request->email)->firstOrFail();

            // Загружаем роли и права для ответа
            $user->load(['roles', 'permissions']);

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Пользователь успешно авторизован',
                'data' => [
                    'user' => new UserResource($user),
                    'token' => $token,
                    'token_type' => 'Bearer',
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при авторизации',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Получение информации о текущем пользователе
     */
    public function me(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $user->load(['roles', 'permissions']);

            return response()->json([
                'success' => true,
                'data' => [
                    'user' => new UserResource($user)
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при получении данных пользователя',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Выход пользователя (удаление токена)
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Пользователь успешно вышел из системы'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при выходе из системы',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Запрос на сброс пароля
     */
    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        try {
            $status = Password::sendResetLink($request->only('email'));


            if ($status === Password::RESET_LINK_SENT) {
                // ЗДЕСЬ логируем токен в лог-файл
                $token = app('auth.password.broker')->createToken(User::where('email', $request->email)->first());
                \Log::info('TOKEN для сброса пароля: ' . $token);

                return response()->json([
                    'success' => true,
                    'message' => 'Ссылка для сброса пароля отправлена на email'
                ], 200);
            }

            return response()->json([
                'success' => false,
                'message' => 'Не удалось отправить ссылку для сброса пароля'
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при отправке ссылки для сброса пароля',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Сброс пароля
     */
    public function resetPassword(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'token' => 'required',
                'email' => 'required|email',
                'password' => 'required|min:8|confirmed',
            ]);

            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function (User $user, string $password) {
                    $user->forceFill([
                        'password' => Hash::make($password)
                    ])->setRememberToken(Str::random(60));

                    $user->save();
                }
            );

            if ($status === Password::PASSWORD_RESET) {
                return response()->json([
                    'success' => true,
                    'message' => 'Пароль успешно сброшен'
                ], 200);
            }

            return response()->json([
                'success' => false,
                'message' => 'Не удалось сбросить пароль'
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при сбросе пароля',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
