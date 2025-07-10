#!/bin/bash
set -e

# Configurar el puerto para Apache
PORT=${PORT:-80}
echo "Listen $PORT" > /etc/apache2/ports.conf
sed -i "s/*:80/*:$PORT/g" /etc/apache2/sites-available/000-default.conf

# Configurar variables de entorno de Laravel
export APP_ENV=${APP_ENV:-production}
export APP_DEBUG=${APP_DEBUG:-false}
export APP_URL=${APP_URL:-http://localhost}
export LOG_CHANNEL=${LOG_CHANNEL:-stderr}

# Si APP_KEY no está definida, generar una nueva
if [ -z "$APP_KEY" ]; then
    echo "Generando APP_KEY..."
    php artisan key:generate --force
fi

# Asegurar permisos correctos
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Limpiar cache
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

# Recrear cache
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Iniciar Apache
exec apache2-foreground
