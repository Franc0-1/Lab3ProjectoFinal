# 🍕 Guía de Deploy - La Que Va Pizzería

## 📋 Requisitos del Servidor

### Requisitos Mínimos
- **PHP**: 8.1 o superior
- **Node.js**: 18.x o superior
- **NPM**: 9.x o superior
- **Composer**: 2.x
- **MySQL**: 8.0 o superior (o MariaDB 10.3+)
- **Redis**: 6.x o superior (opcional pero recomendado)
- **Nginx/Apache**: Configurado para Laravel

### Extensiones de PHP Requeridas
```
- BCMath
- Ctype
- JSON
- Mbstring
- OpenSSL
- PDO
- PDO_mysql
- Tokenizer
- XML
- GD (para manipulación de imágenes)
- Redis (opcional)
```

## 🚀 Proceso de Deploy

### 1. Preparación del Servidor

```bash
# Clonar el repositorio
git clone https://github.com/tu-usuario/laqueva-laravel.git
cd laqueva-laravel

# Dar permisos de ejecución al script de deploy
chmod +x deploy.sh
```

### 2. Configuración de Base de Datos

```sql
-- Crear base de datos
CREATE DATABASE laqueva_production CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Crear usuario
CREATE USER 'laqueva_user'@'localhost' IDENTIFIED BY 'tu_password_seguro';
GRANT ALL PRIVILEGES ON laqueva_production.* TO 'laqueva_user'@'localhost';
FLUSH PRIVILEGES;
```

### 3. Configuración del Entorno

```bash
# Copiar archivo de configuración
cp .env.production.example .env

# Editar configuración
nano .env

# Generar clave de aplicación
php artisan key:generate
```

### 4. Primera Instalación

```bash
# Instalar dependencias
composer install --no-dev --optimize-autoloader
npm ci --production

# Construir assets
npm run build

# Ejecutar migraciones y seeders
php artisan migrate --seed

# Crear enlace simbólico para storage
php artisan storage:link

# Optimizar para producción
php artisan optimize
```

### 5. Deploy Automático (Deployments Posteriores)

```bash
# Ejecutar script de deploy
./deploy.sh
```

## 📁 Estructura de Archivos para Deploy

```
laqueva-laravel/
├── app/                    # Lógica de la aplicación
├── config/                 # Configuración
├── database/              # Migraciones y seeders
├── public/                # Punto de entrada web
│   ├── assets/           # Imágenes de pizzas
│   └── build/           # Assets compilados
├── resources/            # Vistas y assets fuente
├── routes/              # Definición de rutas
├── storage/             # Archivos generados
├── .env                 # Configuración de entorno
├── .env.production.example # Ejemplo para producción
├── deploy.sh           # Script de deploy
└── DEPLOY_GUIDE.md    # Esta guía
```

## ⚙️ Configuración del Servidor Web

### Nginx (Recomendado)

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name yourdomain.com www.yourdomain.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/laqueva-laravel/public;

    # SSL configuration
    ssl_certificate /path/to/ssl/cert.pem;
    ssl_certificate_key /path/to/ssl/private.key;

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;
    add_header Content-Security-Policy "default-src 'self' http: https: data: blob: 'unsafe-inline'" always;

    index index.php;

    charset utf-8;

    # Handle Laravel routes
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # PHP handling
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    # Security: deny access to hidden files
    location ~ /\. {
        deny all;
    }

    # Optimize static files
    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

### Apache (.htaccess)

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

## 🔒 Seguridad

### 1. Permisos de Archivos
```bash
# Permisos para el propietario del servidor web
chown -R www-data:www-data /var/www/laqueva-laravel
chmod -R 755 /var/www/laqueva-laravel
chmod -R 775 /var/www/laqueva-laravel/storage
chmod -R 775 /var/www/laqueva-laravel/bootstrap/cache
```

### 2. Variables de Entorno Importantes
- `APP_ENV=production`
- `APP_DEBUG=false`
- `SESSION_SECURE_COOKIE=true`
- Usar HTTPS siempre

### 3. Configuración de Firewall
```bash
# Permitir solo puertos necesarios
ufw allow 22/tcp  # SSH
ufw allow 80/tcp  # HTTP
ufw allow 443/tcp # HTTPS
ufw enable
```

## 📊 Monitoreo y Logs

### Ver Logs en Tiempo Real
```bash
# Logs de Laravel
tail -f storage/logs/laravel.log

# Logs de Nginx
tail -f /var/log/nginx/access.log
tail -f /var/log/nginx/error.log

# Logs de sistema
journalctl -f
```

### Comandos de Diagnóstico
```bash
# Estado de la aplicación
php artisan about

# Verificar configuración
php artisan config:show

# Limpiar cachés si hay problemas
php artisan optimize:clear
```

## 🚨 Troubleshooting

### Problemas Comunes

1. **Error 500**: Verificar permisos y logs de error
2. **Assets no cargan**: Verificar que `npm run build` se ejecutó
3. **Base de datos**: Verificar credenciales en `.env`
4. **Redis**: Si no tienes Redis, cambiar drivers a `file` o `database`

### Comandos de Recuperación
```bash
# Reiniciar desde cero
php artisan optimize:clear
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Reconstruir optimizaciones
php artisan optimize
```

## 🔄 Proceso de Actualización

1. **Backup**: Siempre hacer backup de BD y archivos
2. **Mantenimiento**: Activar modo de mantenimiento
3. **Deploy**: Ejecutar script de deploy
4. **Verificar**: Probar funcionalidades críticas
5. **Monitor**: Observar logs por errores

## 📞 Soporte

Si encuentras problemas durante el deploy:
1. Verificar logs de error
2. Revisar configuración de `.env`
3. Verificar permisos de archivos
4. Comprobar que todos los servicios estén ejecutándose

## 🎯 Checklist Post-Deploy

- [ ] Aplicación carga correctamente
- [ ] Login/registro funciona
- [ ] Gestión de pizzas funciona
- [ ] Gestión de clientes funciona
- [ ] Sistema de carrito funciona
- [ ] Emails se envían correctamente
- [ ] Logs no muestran errores críticos
- [ ] Rendimiento es aceptable
- [ ] SSL/HTTPS está configurado
- [ ] Backup está configurado

---

**🍕 ¡Que disfrutes sirviendo las mejores pizzas con La Que Va!**
