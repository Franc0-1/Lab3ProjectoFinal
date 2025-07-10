<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class SetupAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Configura el sistema de administración completo';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Configurando sistema de administración...');

        // Ejecutar migraciones
        $this->info('📊 Ejecutando migraciones...');
        Artisan::call('migrate', ['--force' => true]);

        // Ejecutar seeders
        $this->info('🌱 Ejecutando seeders...');
        Artisan::call('db:seed', ['--class' => 'AdminUserSeeder']);

        // Limpiar cache
        $this->info('🧹 Limpiando caché...');
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');

        // Crear enlace simbólico de storage
        $this->info('🔗 Creando enlace simbólico de storage...');
        Artisan::call('storage:link');

        $this->info('✅ Sistema de administración configurado exitosamente!');
        $this->info('');
        $this->info('🔑 Credenciales de acceso:');
        $this->info('Admin: admin@laqueva.com / admin123');
        $this->info('Empleado: empleado@laqueva.com / empleado123');
        $this->info('Cliente: cliente@laqueva.com / cliente123');
        $this->info('');
        $this->info('🌐 Accede al panel de administración en: /admin/dashboard');
        $this->info('');
        $this->info('📋 Funcionalidades disponibles:');
        $this->info('• Gestión de pizzas');
        $this->info('• Gestión de órdenes');
        $this->info('• Gestión de usuarios');
        $this->info('• Reportes en PDF y Excel');
        $this->info('• Control de inventario');
        $this->info('• Estadísticas avanzadas');
    }
}
