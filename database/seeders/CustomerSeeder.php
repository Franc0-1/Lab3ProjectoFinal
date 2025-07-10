<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = [
            [
                'name' => 'Juan Pérez',
                'email' => 'juan.perez@example.com',
                'phone' => '362-123-4567',
                'address' => 'Av. Sarmiento 123',
                'neighborhood' => 'Centro',
                'frequent_customer' => true,
            ],
            [
                'name' => 'María González',
                'email' => 'maria.gonzalez@example.com',
                'phone' => '362-987-6543',
                'address' => 'Calle Mitre 456',
                'neighborhood' => 'Villa Prosperidad',
                'frequent_customer' => false,
            ],
            [
                'name' => 'Carlos Rodríguez',
                'email' => 'carlos.rodriguez@example.com',
                'phone' => '362-555-0123',
                'address' => 'Av. Alberdi 789',
                'neighborhood' => 'Barrio Norte',
                'frequent_customer' => true,
            ],
            [
                'name' => 'Ana López',
                'email' => 'ana.lopez@example.com',
                'phone' => '362-777-8888',
                'address' => 'Calle Pellegrini 321',
                'neighborhood' => 'Villa Don Andrés',
                'frequent_customer' => false,
            ],
            [
                'name' => 'Luis Martínez',
                'email' => 'luis.martinez@example.com',
                'phone' => '362-444-5555',
                'address' => 'Av. 25 de Mayo 654',
                'neighborhood' => 'Barrio Universitario',
                'frequent_customer' => true,
            ],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }
    }
}
