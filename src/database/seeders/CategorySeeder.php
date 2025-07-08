<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Технологии', 'description' => 'Статьи о технологиях', 'color' => '#3B82F6'],
            ['name' => 'Новости', 'description' => 'Последние новости', 'color' => '#EF4444'],
            ['name' => 'Обучение', 'description' => 'Образовательные материалы', 'color' => '#10B981'],
            ['name' => 'Разработка', 'description' => 'Программирование и разработка', 'color' => '#8B5CF6'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
