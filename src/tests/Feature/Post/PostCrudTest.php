<?php

namespace Tests\Feature\Post;

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class PostCrudTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Создаём разрешения
        Permission::create(['name' => 'create_posts']);
        Permission::create(['name' => 'edit_posts']);
        Permission::create(['name' => 'manage_posts']);

        // Создаём роль и даём ей все разрешения
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(['create_posts', 'edit_posts', 'manage_posts']);

        // Создаём пользователя и назначаем роль admin
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
    }

    #[Test]
    public function author_can_create_post(): void
    {
        Storage::fake('public');

        $category = Category::factory()->create();
        $tag = Tag::factory()->create();

        $token = $this->admin->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/posts', [
                'title' => 'Test Post',
                'content' => 'This is a test post.',
                'status' => 'draft',
                'categories' => [$category->id],
                'tags' => [$tag->id],
                'featured_image' => UploadedFile::fake()->image('cover.jpg'),
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Пост успешно создан',
            ]);

        $this->assertDatabaseHas('posts', [
            'title' => 'Test Post',
            'content' => 'This is a test post.',
            'status' => 'draft',
        ]);
    }

    #[Test]
    public function it_returns_paginated_posts_with_filters(): void
    {
        $this->actingAs($this->admin);

        Post::factory()->count(5)->create(['title' => 'Laravel is great']);
        Post::factory()->count(3)->create(['title' => 'Vue is awesome']);

        $response = $this->getJson('/api/blog/posts?search=laravel');

        $response->assertStatus(200)
            ->assertJsonFragment(['title' => 'Laravel is great']);
    }

    #[Test]
    public function it_can_show_a_single_post(): void
    {
        $this->actingAs($this->admin);

        $post = Post::factory()->create();

        $response = $this->getJson("/api/blog/posts/{$post->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $post->id]);
    }

    #[Test]
    public function author_can_update_their_post(): void
    {
        $this->actingAs($this->admin);

        $post = Post::factory()->create(['author_id' => $this->admin->id]);

        $response = $this->putJson("/api/posts/{$post->id}", [
            'title' => 'Updated Title',
            'content' => 'Updated content',
            'status' => 'draft',
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['title' => 'Updated Title']);
    }

    #[Test]
    public function author_can_delete_their_post(): void
    {
        $this->actingAs($this->admin);

        $post = Post::factory()->create(['author_id' => $this->admin->id]);

        $response = $this->deleteJson("/api/posts/{$post->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Пост успешно удален']);

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }
}
