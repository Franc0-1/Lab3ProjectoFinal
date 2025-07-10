<?php

/**
 * Script para migrar datos de MySQL a PostgreSQL
 * 
 * Uso: php migrate-mysql-to-postgresql.php
 * 
 * Asegúrate de:
 * 1. Tener configuradas las conexiones de MySQL y PostgreSQL en config/database.php
 * 2. Haber ejecutado las migraciones en PostgreSQL
 * 3. Tener permisos de lectura en MySQL y escritura en PostgreSQL
 */

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔄 Iniciando migración de MySQL a PostgreSQL...\n";

// Configuración de conexiones
$mysqlConnection = 'mysql_source'; // Conexión MySQL desde .env
$pgsqlConnection = 'pgsql'; // Tu conexión PostgreSQL actual

// Tablas a migrar (en orden de dependencias)
$tablesToMigrate = [
    'users',
    'roles',
    'permissions',
    'model_has_permissions',
    'model_has_roles',
    'role_has_permissions',
    'categories',
    'customers',
    'pizzas',
    'orders',
    'order_items',
    'promotions',
];

// Opción para manejar datos existentes
$clearExistingData = false; // Cambiar a true si quieres limpiar las tablas antes de migrar
echo "⚙️  Configuración: " . ($clearExistingData ? "Limpiar datos existentes" : "Conservar datos existentes") . "\n";

try {
    // Verificar conexiones
    echo "🔌 Verificando conexión MySQL...\n";
    DB::connection($mysqlConnection)->getPdo();
    echo "✅ Conexión MySQL exitosa\n";
    
    echo "🔌 Verificando conexión PostgreSQL...\n";
    DB::connection($pgsqlConnection)->getPdo();
    echo "✅ Conexión PostgreSQL exitosa\n";
    
    // Nota: No deshabilitamos restricciones de claves foráneas para evitar problemas de permisos
    // En su lugar, migraremos las tablas en orden de dependencias y usaremos transacciones
    echo "📋 Iniciando migración de tablas en orden de dependencias...\n";
    
    foreach ($tablesToMigrate as $table) {
        echo "\n📊 Migrando tabla: $table\n";
        
        // Verificar si la tabla existe en MySQL
        if (!Schema::connection($mysqlConnection)->hasTable($table)) {
            echo "⚠️  Tabla $table no existe en MySQL, saltando...\n";
            continue;
        }
        
        // Verificar si la tabla existe en PostgreSQL
        if (!Schema::connection($pgsqlConnection)->hasTable($table)) {
            echo "❌ Tabla $table no existe en PostgreSQL, ejecuta las migraciones primero\n";
            continue;
        }
        
        // Obtener datos de MySQL
        $data = DB::connection($mysqlConnection)->table($table)->get();
        $count = $data->count();
        
        if ($count === 0) {
            echo "📭 Tabla $table está vacía en MySQL\n";
            continue;
        }
        
        echo "📦 Encontrados $count registros en MySQL\n";
        
        // Verificar si hay datos existentes en PostgreSQL
        $existingCount = DB::connection($pgsqlConnection)->table($table)->count();
        echo "📊 Registros existentes en PostgreSQL: $existingCount\n";
        
        // Limpiar tabla en PostgreSQL si se solicita
        if ($clearExistingData && $existingCount > 0) {
            echo "🗑️  Limpiando datos existentes en PostgreSQL...\n";
            DB::connection($pgsqlConnection)->table($table)->truncate();
            echo "✅ Tabla $table limpiada\n";
        }
        
        // Usar transacción para cada tabla
        DB::connection($pgsqlConnection)->beginTransaction();
        
        try {
            // Insertar datos en lotes
            $batchSize = 10; // Tamaño de lote reducido para evitar sobrecargas
            $batches = $data->chunk($batchSize);
            $inserted = 0;
            
            foreach ($batches as $batchIndex => $batch) {
                $retryCount = 0;
                $maxRetries = 3;
                $batchInserted = false;
                
                while (!$batchInserted && $retryCount < $maxRetries) {
                    try {
                        // Convertir a array para inserción
                        $batchArray = $batch->map(function ($item) {
                            return (array) $item;
                        })->toArray();
                        
                        DB::connection($pgsqlConnection)->table($table)->insert($batchArray);
                        usleep(500000); // Pausa de 0.5 segundos entre lote
                        $inserted += count($batchArray);
                        echo "✅ Insertados $inserted/$count registros (lote " . ($batchIndex + 1) . ")\n";
                        $batchInserted = true;
                        
                    } catch (Exception $e) {
                        $retryCount++;
                        echo "❌ Error insertando lote en tabla $table (intento $retryCount): " . $e->getMessage() . "\n";
                        
                        if ($retryCount >= $maxRetries) {
                            echo "🔄 Intentando insertar registros uno por uno...\n";
                            // Intentar insertar uno por uno si el lote falla
                            foreach ($batch as $record) {
                                try {
                                    DB::connection($pgsqlConnection)->table($table)->insert((array) $record);
                                    $inserted++;
                                } catch (Exception $e2) {
                                    echo "❌ Error insertando registro individual: " . $e2->getMessage() . "\n";
                                }
                            }
                            $batchInserted = true;
                        } else {
                            sleep(1); // Esperar 1 segundo antes de reintentar
                        }
                    }
                }
            }
            
            // Confirmar transacción
            DB::connection($pgsqlConnection)->commit();
            
        } catch (Exception $e) {
            // Revertir transacción en caso de error
            DB::connection($pgsqlConnection)->rollback();
            echo "❌ Error en transacción de tabla $table: " . $e->getMessage() . "\n";
            throw $e;
        }
        
        echo "✅ Migración de tabla $table completada: $inserted registros\n";
        
        // Actualizar secuencias en PostgreSQL para campos auto-incrementales
        if (in_array($table, ['users', 'categories', 'customers', 'pizzas', 'orders', 'order_items', 'promotions'])) {
            try {
                $maxId = DB::connection($pgsqlConnection)->table($table)->max('id');
                if ($maxId) {
                    DB::connection($pgsqlConnection)->statement("SELECT setval('{$table}_id_seq', $maxId);");
                    echo "🔢 Secuencia de $table actualizada a $maxId\n";
                }
            } catch (Exception $e) {
                echo "⚠️  No se pudo actualizar la secuencia de $table: " . $e->getMessage() . "\n";
            }
        }
    }
    
    echo "✅ Migración de datos completada\n";
    
    echo "\n🎉 ¡Migración completada exitosamente!\n";
    echo "🔍 Verifica los datos en PostgreSQL antes de continuar\n";
    
    // Mostrar resumen
    echo "\n📈 Resumen de registros migrados:\n";
    foreach ($tablesToMigrate as $table) {
        if (Schema::connection($pgsqlConnection)->hasTable($table)) {
            $count = DB::connection($pgsqlConnection)->table($table)->count();
            echo "   $table: $count registros\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Error durante la migración: " . $e->getMessage() . "\n";
    echo "🔄 Revertir cambios si es necesario\n";
    exit(1);
}

echo "\n💡 Pasos siguientes:\n";
echo "1. Verifica que todos los datos se migraron correctamente\n";
echo "2. Actualiza tu archivo .env para usar PostgreSQL\n";
echo "3. Prueba la aplicación completamente\n";
echo "4. Haz backup de la base de datos PostgreSQL\n";
echo "5. Actualiza la configuración en Render\n";
