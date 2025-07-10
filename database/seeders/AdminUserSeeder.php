<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

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

        // Crear usuario administrador por defecto
        $admin = User::firstOrCreate(
            ['email' => 'admin@laqueva.com'],
            [
                'name' => 'Administrador',
                'password' => bcrypt('admin123'),
                'email_verified_at' => now(),
            ]
        );

        // Asignar rol de administrador
        $admin->assignRole('admin');

        // Crear usuario empleado de ejemplo
        $employee = User::firstOrCreate(
            ['email' => 'empleado@laqueva.com'],
            [
                'name' => 'Empleado',
                'password' => bcrypt('empleado123'),
                'email_verified_at' => now(),
            ]
        );

        $employee->assignRole('employee');

        // Crear usuario cliente de ejemplo
        $customer = User::firstOrCreate(
            ['email' => 'cliente@laqueva.com'],
            [
                'name' => 'Cliente',
                'password' => bcrypt('cliente123'),
                'email_verified_at' => now(),
            ]
        );

        $customer->assignRole('customer');

        $this->command->info('Usuarios creados exitosamente:');
        $this->command->info('Admin: admin@laqueva.com / admin123');
        $this->command->info('Empleado: empleado@laqueva.com / empleado123');
        $this->command->info('Cliente: cliente@laqueva.com / cliente123');
    }
}
