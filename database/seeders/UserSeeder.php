<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Permissions
        $permissions = [
            'view_dashboard',
            'manage_orders',
            'manage_pizzas',
            'manage_categories',
            'manage_customers',
            'manage_users',
            'manage_promotions',
            'export_reports',
            'view_reports',
            'manage_settings',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create Roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $employeeRole = Role::firstOrCreate(['name' => 'employee']);
        $customerRole = Role::firstOrCreate(['name' => 'customer']);

        // Assign permissions to roles
        $adminRole->givePermissionTo(Permission::all());
        
        $employeeRole->givePermissionTo([
            'view_dashboard',
            'manage_orders',
            'manage_pizzas',
            'manage_categories',
            'manage_customers',
            'view_reports',
        ]);

        $customerRole->givePermissionTo([
            'view_dashboard',
        ]);

        // Create Users
        $admin = User::firstOrCreate(
            ['email' => 'admin@laqueva.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        if (!$admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }

        $employee = User::firstOrCreate(
            ['email' => 'empleado@laqueva.com'],
            [
                'name' => 'Empleado',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        if (!$employee->hasRole('employee')) {
            $employee->assignRole('employee');
        }

        $customer = User::firstOrCreate(
            ['email' => 'cliente@laqueva.com'],
            [
                'name' => 'Cliente Test',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        if (!$customer->hasRole('customer')) {
            $customer->assignRole('customer');
        }
    }
}
