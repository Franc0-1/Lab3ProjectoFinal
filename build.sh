#!/bin/bash

echo "🚀 Iniciando build de Laravel..."

# Instalar dependencias
composer install --no-dev --optimize-autoloader

# Generar clave de aplicación si no existe
php artisan key:generate --ansi --no-interaction

# Optimizar configuraciones para producción
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Instalar dependencias de Node.js si existe package.json
if [ -f "package.json" ]; then
    echo "📦 Instalando dependencias de Node.js..."
    npm ci
    npm run build
fi

echo "✅ Build completado!"
