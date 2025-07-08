<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            ['name' => 'PHP', 'color' => '#777BB4'],
            ['name' => 'Laravel', 'color' => '#FF2D20'],
            ['name' => 'JavaScript', 'color' => '#F7DF1E'],
            ['name' => 'React', 'color' => '#61DAFB'],
            ['name' => 'Vue.js', 'color' => '#4FC08D'],
            ['name' => 'Docker', 'color' => '#2496ED'],
            ['name' => 'API', 'color' => '#FF6B6B'],
        ];

        foreach ($tags as $tag) {
            Tag::create($tag);
        }
    }
}
