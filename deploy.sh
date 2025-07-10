#!/bin/bash

# ============================================
# SCRIPT DE DEPLOY PARA LA QUE VA - PIZZERÍA
# ============================================

echo "🍕 Iniciando deploy de La Que Va - Pizzería..."

# Verificar que estamos en el directorio correcto
if [ ! -f "artisan" ]; then
    echo "❌ Error: No se encontró el archivo artisan. Ejecuta este script desde la raíz del proyecto Laravel."
    exit 1
fi

# 1. Activar modo de mantenimiento
echo "🔧 Activando modo de mantenimiento..."
php artisan down

# 2. Hacer backup de la base de datos (opcional)
echo "💾 Creando backup de la base de datos..."
php artisan backup:run --only-db 2>/dev/null || echo "ℹ️  Backup saltado (comando no disponible)"

# 3. Obtener últimos cambios del repositorio
echo "📥 Obteniendo últimos cambios..."
git pull origin main

# 4. Instalar/actualizar dependencias de Composer (sin dev)
echo "📦 Instalando dependencias de PHP..."
composer install --no-dev --optimize-autoloader --no-interaction

# 5. Instalar/actualizar dependencias de NPM
echo "📦 Instalando dependencias de Node.js..."
npm ci --production

# 6. Construir assets para producción
echo "🔨 Construyendo assets..."
npm run build

# 7. Limpiar y optimizar cachés
echo "🧹 Limpiando cachés..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 8. Optimizar para producción
echo "⚡ Optimizando aplicación..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 9. Ejecutar migraciones
echo "🗄️  Ejecutando migraciones..."
php artisan migrate --force

# 10. Generar rutas Ziggy
echo "🗺️  Generando rutas Ziggy..."
php artisan ziggy:generate

# 11. Crear enlace simbólico para storage (si no existe)
echo "🔗 Verificando enlace de storage..."
php artisan storage:link

# 12. Ajustar permisos
echo "🔐 Ajustando permisos..."
chmod -R 755 storage bootstrap/cache
chmod -R 775 storage/logs storage/framework storage/app

# 13. Desactivar modo de mantenimiento
echo "✅ Desactivando modo de mantenimiento..."
php artisan up

echo ""
echo "🎉 ¡Deploy completado exitosamente!"
echo "🍕 La Que Va - Pizzería está lista para servir!"
echo ""
echo "📊 Estado de la aplicación:"
php artisan about --only=environment

echo ""
echo "🔍 Para verificar el estado:"
echo "   php artisan queue:work (si usas queues)"
echo "   php artisan horizon (si usas Laravel Horizon)"
echo "   tail -f storage/logs/laravel.log (para ver logs)"
