<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Pizzas Clásicas',
                'description' => 'Las pizzas tradicionales de siempre',
                'active' => true,
                'sort_order' => 1
            ],
            [
                'name' => 'Pizzas Especiales',
                'description' => 'Pizzas con ingredientes únicos y especiales',
                'active' => true,
                'sort_order' => 2
            ],
            [
                'name' => 'Pizzas Gourmet',
                'description' => 'Pizzas premium con ingredientes selectos',
                'active' => true,
                'sort_order' => 3
            ],
            [
                'name' => 'Promociones',
                'description' => 'Ofertas especiales y combos',
                'active' => true,
                'sort_order' => 4
            ],
            [
                'name' => 'Pizzas Vegetarianas',
                'description' => 'Opciones sin carne para vegetarianos',
                'active' => true,
                'sort_order' => 5
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
