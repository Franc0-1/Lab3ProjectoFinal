<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear roles si no existen
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $employeeRole = Role::firstOrCreate(['name' => 'employee']);
        $customerRole = Role::firstOrCreate(['name' => 'customer']);

        // Crear permisos básicos
        $permissions = [
            'manage_pizzas',
            'manage_orders',
            'manage_customers',
            'manage_users',
            'view_reports',
            'manage_categories',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Asignar todos los permisos al rol admin
        $adminRole->syncPermissions($permissions);

        // Las contraseñas se leen de SEED_*_PASSWORD; si no están definidas se genera una aleatoria.
        // Nunca se guardan en el código y solo se muestran al crear el usuario por primera vez.
        $users = [
            ['email' => 'admin@laqueva.com', 'name' => 'Administrador', 'role' => 'admin', 'env' => 'SEED_ADMIN_PASSWORD'],
            ['email' => 'empleado@laqueva.com', 'name' => 'Empleado', 'role' => 'employee', 'env' => 'SEED_EMPLOYEE_PASSWORD'],
            ['email' => 'cliente@laqueva.com', 'name' => 'Cliente', 'role' => 'customer', 'env' => 'SEED_CUSTOMER_PASSWORD'],
        ];

        foreach ($users as $data) {
            $configured = env($data['env']);
            $password = $configured ?: Str::password(16, symbols: false);

            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => bcrypt($password),
                    'email_verified_at' => now(),
                ]
            );

            $user->assignRole($data['role']);

            if (!$user->wasRecentlyCreated) {
                $this->command->info("{$data['email']} ya existía: no se modificó su contraseña.");
            } elseif ($configured) {
                $this->command->info("{$data['email']} creado con la contraseña de {$data['env']}.");
            } else {
                $this->command->warn("{$data['email']} creado. Contraseña generada (se muestra una sola vez): {$password}");
            }
        }
    }
}
