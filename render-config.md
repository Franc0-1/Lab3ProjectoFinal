# Configuración para Render

## Variables de Entorno Necesarias en Render

Asegúrate de configurar estas variables en tu servicio de Render:

### Variables Básicas de Laravel
```
APP_NAME="La Queva Pizzería"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://laquevapizzeria.onrender.com
```

### Base de Datos (PostgreSQL)
```
DB_CONNECTION=pgsql
DB_HOST=[tu_host_de_postgres]
DB_PORT=5432
DB_DATABASE=[nombre_de_tu_bd]
DB_USERNAME=[usuario_de_bd]
DB_PASSWORD=[contraseña_de_bd]
```

### Configuración de Sesiones y Cache
```
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```

### Logs
```
LOG_CHANNEL=stderr
LOG_LEVEL=error
```

### Otros
```
BCRYPT_ROUNDS=12
```

## Pasos para Configurar en Render

1. Ve a tu dashboard de Render
2. Selecciona tu servicio web
3. Ve a la pestaña "Environment"
4. Agrega todas las variables de arriba
5. Haz un nuevo deploy

## Comandos de Diagnóstico

Si el error persiste, puedes usar estos comandos en el shell de Render:

```bash
# Verificar estado de la aplicación
php artisan route:list
php artisan migrate:status
php artisan config:show database

# Verificar logs
tail -f storage/logs/laravel.log
```

## Posibles Causas del Error 500

1. **Migraciones no ejecutadas** - Solucionado con el nuevo build.sh
2. **Variables de entorno faltantes** - Verificar en Render
3. **Problemas de permisos** - Render generalmente maneja esto
4. **Errores de sintaxis** - Revisar logs de Render
5. **Dependencias faltantes** - Verificar composer.json

## Verificación Post-Deploy

Una vez que hagas el deploy, verifica:

1. Que todas las migraciones se ejecutaron correctamente
2. Que la aplicación puede conectarse a la base de datos
3. Que los assets se construyeron correctamente
4. Que no hay errores de PHP en los logs
