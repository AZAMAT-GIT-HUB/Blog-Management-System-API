<?php
namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use App\Models\User;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register()
    {
        // Создаем роль reader, чтобы не было ошибки
        Role::create(['name' => 'reader']);

        $response = $this->postJson('/api/auth/register', [
            'name' => 'Azamat',
            'email' => 'azamat@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Пользователь успешно зарегистрирован',
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'azamat@example.com',
        ]);
    }
}
