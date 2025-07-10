#!/bin/bash
set -e

echo "🚀 Iniciando aplicación Laravel..."

# Verificar que estamos en el directorio correcto
cd /var/www/html

# Crear archivo .env si no existe
if [ ! -f .env ]; then
    echo "📄 Creando archivo .env..."
    if [ -f .env.example ]; then
        cp .env.example .env
    else
        echo "⚠️  Archivo .env.example no encontrado, creando .env básico..."
        cat > .env << EOF
APP_NAME=Laravel
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=http://localhost

LOG_CHANNEL=stderr
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
EOF
    fi
fi

# Generar clave de aplicación si no existe
if ! grep -q "APP_KEY=base64:" .env; then
    echo "🔑 Generando clave de aplicación..."
    php artisan key:generate --no-interaction --force
fi

# Ejecutar migraciones si están configuradas
# Descomenta la siguiente línea si usas base de datos
# echo "🗄️  Ejecutando migraciones..."
# php artisan migrate --force --no-interaction

# Optimizar para producción
echo "⚡ Optimizando aplicación..."
php artisan config:cache --no-interaction
php artisan route:cache --no-interaction
php artisan view:cache --no-interaction

# Configurar permisos finales
echo "🔒 Configurando permisos..."
chown -R www-data:www-data /var/www/html
chmod -R 755 storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

echo "✅ Aplicación lista!"
echo "🌐 Iniciando servidor web..."

# Iniciar Apache
exec apache2-foreground
