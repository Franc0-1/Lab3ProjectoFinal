#!/bin/bash
set -e

echo "=== Iniciando configuración de Laravel ==="

# Configurar el puerto para Apache
PORT=${PORT:-10000}
echo "Configurando Apache en puerto: $PORT"

# Configurar ports.conf
echo "Listen $PORT" > /etc/apache2/ports.conf

# Configurar el VirtualHost con el puerto correcto
sed -i "s/\${PORT}/$PORT/g" /etc/apache2/sites-available/000-default.conf

# Asegurar que Apache no escuche en el puerto 80
sed -i '/Listen 80/d' /etc/apache2/ports.conf

# Configurar variables de entorno de Laravel
export APP_ENV=${APP_ENV:-production}
export APP_DEBUG=${APP_DEBUG:-false}
export APP_URL=${APP_URL:-http://localhost}
export LOG_CHANNEL=${LOG_CHANNEL:-stderr}

echo "Variables de entorno configuradas:"
echo "APP_ENV: $APP_ENV"
echo "APP_DEBUG: $APP_DEBUG"
echo "APP_URL: $APP_URL"
echo "LOG_CHANNEL: $LOG_CHANNEL"

# Verificar que .env existe
if [ ! -f ".env" ]; then
    echo "Creando archivo .env desde .env.example"
    cp .env.example .env || true
fi

# Si APP_KEY no está definida, generar una nueva
if [ -z "$APP_KEY" ]; then
    echo "Generando APP_KEY..."
    php artisan key:generate --force
else
    echo "APP_KEY ya está configurada"
fi

# Asegurar permisos correctos
echo "Configurando permisos..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Verificar estructura de directorios
echo "Verificando directorios de Laravel..."
ls -la /var/www/html/
ls -la /var/www/html/public/

# Limpiar cache
echo "Limpiando cache..."
php artisan config:clear || echo "Error limpiando config cache"
php artisan route:clear || echo "Error limpiando route cache"
php artisan view:clear || echo "Error limpiando view cache"

# Recrear cache
echo "Recreando cache..."
php artisan config:cache || echo "Error creando config cache"
php artisan route:cache || echo "Error creando route cache"
php artisan view:cache || echo "Error creando view cache"

# Verificar configuración de Laravel
echo "Verificando configuración de Laravel..."
php artisan config:show app.key || echo "No se pudo mostrar APP_KEY"

# Habilitar logs de PHP para debug
echo "Habilitando logs de PHP..."
echo "log_errors = On" >> /usr/local/etc/php/php.ini
echo "error_log = /var/log/php_errors.log" >> /usr/local/etc/php/php.ini

echo "=== Configuración completada, iniciando Apache ==="

# Iniciar Apache
exec apache2-foreground
