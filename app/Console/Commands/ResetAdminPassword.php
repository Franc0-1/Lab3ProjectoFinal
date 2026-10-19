<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ResetAdminPassword extends Command
{
    protected $signature = 'admin:reset-password {--email=admin@laqueva.com : Email del usuario administrador}';
    protected $description = 'Cambia la contraseña del administrador pidiéndola de forma oculta';

    public function handle()
    {
        $email = $this->option('email');
        $admin = User::where('email', $email)->first();

        if (!$admin) {
            $this->error("No se encontró el usuario {$email}.");
            return 1;
        }

        // La contraseña se escribe de forma oculta: no queda en el código ni se imprime.
        $password = $this->secret('Nueva contraseña (mínimo 8 caracteres)');
        $confirmation = $this->secret('Repetir contraseña');

        if (!is_string($password) || strlen($password) < 8) {
            $this->error('La contraseña debe tener al menos 8 caracteres.');
            return 1;
        }

        if ($password !== $confirmation) {
            $this->error('Las contraseñas no coinciden.');
            return 1;
        }

        $admin->password = Hash::make($password);
        $admin->save();

        $this->info("Contraseña actualizada para {$email}.");

        // Verificar roles
        $roles = $admin->getRoleNames();
        $this->info('Roles: ' . $roles->implode(', '));

        if (!$admin->hasRole('admin')) {
            $this->warn('El rol admin no estaba asignado. Asignando...');
            $admin->assignRole('admin');
            $this->info('Rol admin asignado.');
        }

        return 0;
    }
}
