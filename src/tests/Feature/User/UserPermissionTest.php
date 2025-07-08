<?php


namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class UserPermissionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Создаём роль admin
        $adminRole = Role::create(['name' => 'admin']);

        // Создаём все необходимые разрешения
        $permissions = [
            'manage_posts',
            'publish_posts',
            'manage_categories',
            'manage_users',
            'access_admin_panel',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Назначаем все разрешения роли admin
        $adminRole->givePermissionTo($permissions);
    }


    public function test_admin_can_access_user_list()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $token = $admin->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/api/admin/users');

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'pagination']);
    }


    public function test_guest_cannot_access_user_list()
    {
        $response = $this->getJson('/api/admin/users');
        $response->assertStatus(401); // Unauthorized
    }


    public function test_admin_can_assign_roles()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $token = $admin->createToken('auth_token')->plainTextToken;

        $user = User::factory()->create();
        Role::create(['name' => 'editor']);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson("/api/admin/users/{$user->id}/assign-role", [
                'role' => 'editor',
            ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Роль успешно назначена']);

        $this->assertTrue($user->fresh()->hasRole('editor'));
    }


    public function test_admin_can_get_all_roles()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $token = $admin->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/api/admin/roles');

        $response->assertStatus(200)
            ->assertJsonStructure(['data']);
    }


    public function test_admin_can_view_all_permissions()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $token = $admin->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/api/admin/permissions');

        $response->assertStatus(200)
            ->assertJsonStructure(['data']);
    }


    public function test_admin_profile_returns_abilities()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $token = $admin->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/api/auth/profile');

        $response->assertStatus(200)
            ->assertJsonStructure(['data' => ['user', 'abilities']]);
    }


    public function test_guest_cannot_access_profile()
    {
        $response = $this->getJson('/api/auth/profile');
        $response->assertStatus(401);
    }
}
