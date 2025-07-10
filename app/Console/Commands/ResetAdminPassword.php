<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ResetAdminPassword extends Command
{
    protected $signature = 'admin:reset-password';
    protected $description = 'Reset admin password to default';

    public function handle()
    {
        $admin = User::where('email', 'admin@laqueva.com')->first();
        
        if (!$admin) {
            $this->error('Admin user not found!');
            return 1;
        }

        $admin->password = Hash::make('admin123');
        $admin->save();

        $this->info('Admin password reset successfully!');
        $this->info('Email: admin@laqueva.com');
        $this->info('Password: admin123');
        
        // Verificar roles
        $roles = $admin->getRoleNames();
        $this->info('Admin roles: ' . $roles->implode(', '));
        
        if (!$admin->hasRole('admin')) {
            $this->warn('Admin role not assigned. Assigning now...');
            $admin->assignRole('admin');
            $this->info('Admin role assigned successfully!');
        }
        
        return 0;
    }
}
