<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Pizza;

class PizzaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create categories
        $pizzaCategory = Category::create([
            'name' => 'Pizzas',
            'description' => 'Deliciosas pizzas caseras',
            'image' => 'assets/pizzas/pizza-4.webp',
            'active' => true,
            'sort_order' => 1
        ]);

        $promoCategory = Category::create([
            'name' => 'Promociones',
            'description' => 'Promociones especiales',
            'image' => 'assets/pizzas/pizza-4.webp',
            'active' => true,
            'sort_order' => 2
        ]);

        // Create pizzas
        $pizzas = [
            [
                'category_id' => $pizzaCategory->id,
                'name' => 'MUZZARELLA',
                'description' => 'Pizza clásica con muzza y oregano',
                'price' => 5500,
                'image' => 'assets/pizzas/pizza-4.webp',
                'ingredients' => ["Salsa", "muzza", "oregano"],
                'available' => true,
                'featured' => false,
                'preparation_time' => 15,
            ],
            [
                'category_id' => $pizzaCategory->id,
                'name' => 'MUZZA CON JAMON',
                'description' => 'Pizza muzzarella con jamón',
                'price' => 6000,
                'image' => 'assets/pizzas/pizza-4.webp',
                'ingredients' => ["Salsa", "muzza", "jamon", "oregano"],
                'available' => true,
                'featured' => false,
                'preparation_time' => 15,
            ],
            [
                'category_id' => $pizzaCategory->id,
                'name' => 'FUGAZZETA',
                'description' => 'Pizza fugazzeta con cebolla',
                'price' => 6500,
                'image' => 'assets/pizzas/pizza-4.webp',
                'ingredients' => ["Salsa", "muzza", "cebolla", "oregano"],
                'available' => true,
                'featured' => true,
                'preparation_time' => 18,
            ],
            [
                'category_id' => $pizzaCategory->id,
                'name' => 'NAPOLITANA',
                'description' => 'Pizza napolitana con tomates',
                'price' => 6500,
                'image' => 'assets/pizzas/pizza-4.webp',
                'ingredients' => ["Salsa", "muzza", "tomates", "oregano", "aceite de ajo"],
                'available' => true,
                'featured' => true,
                'preparation_time' => 18,
            ],
            [
                'category_id' => $pizzaCategory->id,
                'name' => 'NAPO CON JAMON',
                'description' => 'Pizza napolitana con jamón',
                'price' => 7000,
                'image' => 'assets/pizzas/pizza-4.webp',
                'ingredients' => ["Salsa", "muzza", "jamon", "tomates", "oregano"],
                'available' => true,
                'featured' => false,
                'preparation_time' => 20,
            ],
            [
                'category_id' => $pizzaCategory->id,
                'name' => 'FUGA CON JAMON',
                'description' => 'Pizza fugazzeta con jamón',
                'price' => 7000,
                'image' => 'assets/pizzas/pizza-4.webp',
                'ingredients' => ["Salsa", "muzza", "jamon", "cebolla", "oregano"],
                'available' => true,
                'featured' => false,
                'preparation_time' => 20,
            ],
            [
                'category_id' => $pizzaCategory->id,
                'name' => 'ESPECIAL',
                'description' => 'Pizza especial de la casa',
                'price' => 7000,
                'image' => 'assets/pizzas/pizza-4.webp',
                'ingredients' => ["Salsa", "muzza", "jamon", "tomates", "cebolla", "oregano"],
                'available' => true,
                'featured' => true,
                'preparation_time' => 22,
            ],
        ];

        // Create promotion pizzas
        $promotions = [
            [
                'category_id' => $promoCategory->id,
                'name' => '2 MUZZA',
                'description' => 'Promoción: 2 Pizzas Muzzarella',
                'price' => 10000,
                'image' => 'assets/pizzas/pizza-4.webp',
                'ingredients' => ["2 Pizzas Muzzarella"],
                'available' => true,
                'featured' => true,
                'preparation_time' => 30,
            ],
            [
                'category_id' => $promoCategory->id,
                'name' => '1 MUZZA + 1 ESPECIAL',
                'description' => 'Promoción: 1 Pizza Muzzarella + 1 Pizza Especial',
                'price' => 12000,
                'image' => 'assets/pizzas/pizza-4.webp',
                'ingredients' => ["1 Pizza Muzzarella", "1 Pizza Especial"],
                'available' => true,
                'featured' => true,
                'preparation_time' => 35,
            ],
            [
                'category_id' => $promoCategory->id,
                'name' => '1 NAPO + 1 FUGA',
                'description' => 'Promoción: 1 Pizza Napolitana + 1 Pizza Fugazzeta',
                'price' => 12000,
                'image' => 'assets/pizzas/pizza-4.webp',
                'ingredients' => ["1 Pizza Napolitana", "1 Pizza Fugazzeta"],
                'available' => true,
                'featured' => true,
                'preparation_time' => 38,
            ],
            [
                'category_id' => $promoCategory->id,
                'name' => '1 NAPO + 1 FUGA (CON JAMÓN)',
                'description' => 'Promoción: 1 Pizza Napolitana con Jamón + 1 Pizza Fugazzeta con Jamón',
                'price' => 13000,
                'image' => 'assets/pizzas/pizza-4.webp',
                'ingredients' => ["1 Pizza Napolitana con Jamón", "1 Pizza Fugazzeta con Jamón"],
                'available' => true,
                'featured' => true,
                'preparation_time' => 40,
            ],
        ];

        // Insert pizzas
        foreach ($pizzas as $pizza) {
            Pizza::create($pizza);
        }

        // Insert promotions
        foreach ($promotions as $promotion) {
            Pizza::create($promotion);
        }
    }
}
