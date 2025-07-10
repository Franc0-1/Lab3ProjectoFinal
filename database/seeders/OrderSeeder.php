<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Pizza;
use App\Models\OrderItem;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = Customer::all();
        $pizzas = Pizza::all();
        
        if ($customers->isEmpty() || $pizzas->isEmpty()) {
            $this->command->info('No hay clientes o pizzas para crear órdenes');
            return;
        }

        // Crear órdenes de prueba
        $orders = [
            [
                'order_number' => 'ORD-0001',
                'customer_id' => $customers->random()->id,
                'status' => 'delivered',
                'delivery_method' => 'pickup',
                'payment_method' => 'cash',
                'subtotal' => 12000,
                'delivery_fee' => 0,
                'total' => 12000,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
                'delivered_at' => now()->subDays(5),
            ],
            [
                'order_number' => 'ORD-0002',
                'customer_id' => $customers->random()->id,
                'status' => 'delivered',
                'delivery_method' => 'delivery',
                'payment_method' => 'transfer',
                'subtotal' => 7000,
                'delivery_fee' => 500,
                'total' => 7500,
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
                'delivered_at' => now()->subDays(3),
            ],
            [
                'order_number' => 'ORD-0003',
                'customer_id' => $customers->random()->id,
                'status' => 'pending',
                'delivery_method' => 'pickup',
                'payment_method' => 'cash',
                'subtotal' => 5500,
                'delivery_fee' => 0,
                'total' => 5500,
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ],
            [
                'order_number' => 'ORD-0004',
                'customer_id' => $customers->random()->id,
                'status' => 'delivered',
                'delivery_method' => 'delivery',
                'payment_method' => 'card',
                'subtotal' => 13000,
                'delivery_fee' => 800,
                'total' => 13800,
                'created_at' => now()->subDays(7),
                'updated_at' => now()->subDays(7),
                'delivered_at' => now()->subDays(7),
            ],
            [
                'order_number' => 'ORD-0005',
                'customer_id' => $customers->random()->id,
                'status' => 'delivered',
                'delivery_method' => 'pickup',
                'payment_method' => 'cash',
                'subtotal' => 10000,
                'delivery_fee' => 0,
                'total' => 10000,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
                'delivered_at' => now()->subDays(2),
            ],
        ];

        foreach ($orders as $orderData) {
            $order = Order::create($orderData);
            
            // Crear items para cada orden
            $numItems = rand(1, 3);
            for ($i = 0; $i < $numItems; $i++) {
                $pizza = $pizzas->random();
                $quantity = rand(1, 2);
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'pizza_id' => $pizza->id,
                    'quantity' => $quantity,
                    'unit_price' => $pizza->price,
                    'total_price' => $pizza->price * $quantity,
                ]);
            }
        }
    }
}
