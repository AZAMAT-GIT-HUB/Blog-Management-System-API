<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Создание разрешений
        $permissions = [
            // Управление постами
            'manage_posts',        // Полное управление всеми постами
            'create_posts',        // Создание постов
            'edit_posts',          // Редактирование постов
            'delete_posts',        // Удаление постов
            'publish_posts',       // Публикация постов
            'view_all_posts',      // Просмотр всех постов (включая черновики)

            // Управление категориями
            'manage_categories',   // Полное управление категориями
            'create_categories',   // Создание категорий
            'edit_categories',     // Редактирование категорий
            'delete_categories',   // Удаление категорий

            // Управление тегами
            'manage_tags',         // Полное управление тегами
            'create_tags',         // Создание тегов
            'edit_tags',           // Редактирование тегов
            'delete_tags',         // Удаление тегов

            // Управление пользователями
            'manage_users',        // Полное управление пользователями
            'view_users',          // Просмотр списка пользователей
            'edit_users',          // Редактирование пользователей
            'delete_users',        // Удаление пользователей
            'assign_roles',        // Назначение ролей

            // Управление системой
            'access_admin_panel',  // Доступ к админ панели
            'view_analytics',      // Просмотр аналитики
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Создание ролей и назначение разрешений

        // Роль: Reader (Читатель) - базовые права
        $readerRole = Role::create(['name' => 'reader']);
        // Читатель не имеет специальных разрешений - только чтение публичного контента

        // Роль: Author (Автор) - может создавать и управлять своими постами
        $authorRole = Role::create(['name' => 'author']);
        $authorRole->givePermissionTo([
            'create_posts',
            'edit_posts',
            'delete_posts',
            'view_all_posts',
        ]);

        // Роль: Editor (Редактор) - может управлять постами, категориями, тегами
        $editorRole = Role::create(['name' => 'editor']);
        $editorRole->givePermissionTo([
            'manage_posts',
            'create_posts',
            'edit_posts',
            'delete_posts',
            'publish_posts',
            'view_all_posts',
            'manage_categories',
            'create_categories',
            'edit_categories',
            'delete_categories',
            'manage_tags',
            'create_tags',
            'edit_tags',
            'delete_tags',
            'view_users',
            'access_admin_panel',
            'view_analytics',
        ]);

        // Роль: Admin (Администратор) - полные права
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        // Создание тестовых пользователей с ролями
        $this->createTestUsers($adminRole, $editorRole, $authorRole, $readerRole);
    }

    private function createTestUsers($adminRole, $editorRole, $authorRole, $readerRole): void
    {
        // Администратор
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@blog.local',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
        ]);
        $admin->assignRole($adminRole);

        // Редактор
        $editor = User::create([
            'name' => 'Editor User',
            'email' => 'editor@blog.local',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
        ]);
        $editor->assignRole($editorRole);

        // Автор
        $author = User::create([
            'name' => 'Author User',
            'email' => 'author@blog.local',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
        ]);
        $author->assignRole($authorRole);

        // Читатель
        $reader = User::create([
            'name' => 'Reader User',
            'email' => 'reader@blog.local',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
        ]);
        $reader->assignRole($readerRole);
    }
}
