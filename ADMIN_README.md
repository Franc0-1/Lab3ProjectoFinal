# Sistema de Administración - La Que Va 🍕

## Descripción

Sistema completo de administración para la pizzería "La Que Va" que incluye gestión de pizzas, órdenes, usuarios y reportes avanzados con generación de PDF utilizando DomPDF.

## Características Principales

### 🔐 Autenticación y Roles
- **Admin**: Acceso completo al sistema
- **Empleado**: Acceso limitado a operaciones básicas
- **Cliente**: Acceso solo a funciones de cliente

### 📊 Panel de Administración
- Dashboard con estadísticas en tiempo real
- Gestión completa de pizzas (CRUD)
- Gestión de órdenes con cambio de estado
- Gestión de usuarios y roles
- Reportes avanzados en PDF y Excel

### 📈 Reportes Disponibles
- **Pizzas**: Listado completo con categorías
- **Órdenes**: Detalle de pedidos por período
- **Clientes**: Información y estadísticas
- **Ventas**: Análisis de ingresos por período
- **Clientes frecuentes**: Identificación de mejores clientes
- **Reporte general**: Resumen completo del negocio

## Instalación y Configuración

### 1. Configurar la Base de Datos

```bash
# Ejecutar migraciones
php artisan migrate

# Ejecutar seeders para crear usuarios de prueba
php artisan db:seed --class=AdminUserSeeder
```

### 2. Configuración Rápida

```bash
# Comando para configurar todo el sistema automáticamente
php artisan admin:setup
```

Este comando ejecuta:
- Migraciones de base de datos
- Seeders para usuarios de prueba
- Limpieza de caché
- Creación de enlace simbólico para storage

### 3. Credenciales de Acceso

**Administrador:**
- Email: `admin@laqueva.com`
- Contraseña: `admin123`

**Empleado:**
- Email: `empleado@laqueva.com`
- Contraseña: `empleado123`

**Cliente:**
- Email: `cliente@laqueva.com`
- Contraseña: `cliente123`

## Acceso al Sistema

### Panel de Administración
- URL: `/admin/dashboard`
- Solo usuarios con rol "admin" pueden acceder

### Rutas Principales
- **Dashboard**: `/admin/dashboard`
- **Pizzas**: `/admin/pizzas`
- **Órdenes**: `/admin/orders`
- **Usuarios**: `/admin/users`
- **Reportes**: `/reports`

## Funcionalidades Detalladas

### 🍕 Gestión de Pizzas
- Crear, editar, eliminar pizzas
- Cambiar disponibilidad
- Gestión de imágenes
- Categorización
- Control de precios

### 📋 Gestión de Órdenes
- Visualizar todas las órdenes
- Cambiar estado en tiempo real
- Filtros por fecha, cliente, estado
- Detalles completos de cada orden
- Estadísticas de órdenes

### 👥 Gestión de Usuarios
- Listar todos los usuarios
- Cambiar roles dinámicamente
- Buscar por nombre/email
- Filtrar por rol
- Estadísticas de usuarios

### 📊 Sistema de Reportes

#### Reportes PDF
- **Pizzas**: `/reports/pizzas/pdf`
- **Órdenes**: `/reports/orders/pdf`
- **Clientes**: `/reports/customers/pdf`
- **Ventas**: `/reports/sales/pdf`
- **Clientes Frecuentes**: `/reports/frequent-customers/pdf`
- **Reporte General**: `/reports/general/pdf`

#### Reportes Excel
- **Pizzas**: `/reports/pizzas/excel`
- **Órdenes**: `/reports/orders/excel`
- **Clientes**: `/reports/customers/excel`
- **Categorías**: `/reports/categories/excel`

## Estructura del Proyecto

### Controladores
- `App\Http\Controllers\Admin\DashboardController`
- `App\Http\Controllers\Admin\PizzaController`
- `App\Http\Controllers\Admin\OrderController`
- `App\Http\Controllers\Admin\UserController`
- `App\Http\Controllers\ReportController`

### Middleware
- `AdminMiddleware`: Restringe acceso solo a usuarios admin

### Vistas
- `resources/views/admin/layout.blade.php`: Layout principal
- `resources/views/admin/dashboard.blade.php`: Dashboard
- `resources/views/admin/pizzas/`: Vistas de pizzas
- `resources/views/admin/orders/`: Vistas de órdenes
- `resources/views/admin/users/`: Vistas de usuarios

### Modelos
- `User`: Gestión de usuarios con roles
- `Pizza`: Gestión de pizzas
- `Order`: Gestión de órdenes
- `Customer`: Gestión de clientes

## Tecnologías Utilizadas

- **Laravel 11**: Framework principal
- **Spatie Permission**: Gestión de roles y permisos
- **DomPDF**: Generación de reportes PDF
- **Maatwebsite Excel**: Exportación a Excel
- **Tailwind CSS**: Estilos del dashboard
- **Font Awesome**: Iconos

## Personalización

### Agregar Nuevos Reportes
1. Crear método en `ReportController`
2. Crear vista Blade para el PDF
3. Añadir ruta en `web.php`

### Modificar Permisos
Editar `AdminUserSeeder` para agregar nuevos permisos:
```php
$permissions = [
    'manage_pizzas',
    'manage_orders',
    'manage_customers',
    'manage_users',
    'view_reports',
    'manage_categories',
    'nuevo_permiso', // Agregar aquí
];
```

### Personalizar Dashboard
Editar `resources/views/admin/dashboard.blade.php` para:
- Cambiar estadísticas mostradas
- Añadir nuevos widgets
- Modificar el diseño

## Mantenimiento

### Comandos Útiles
```bash
# Limpiar caché
php artisan cache:clear

# Reconfigurar permisos
php artisan db:seed --class=AdminUserSeeder

# Crear enlace simbólico
php artisan storage:link

# Ejecutar migraciones
php artisan migrate
```

### Backup de Datos
Se recomienda hacer respaldos regulares de:
- Base de datos
- Archivos de storage (imágenes de pizzas)
- Configuración del sistema

## Soporte

Para dudas o problemas:
1. Revisar logs en `storage/logs/laravel.log`
2. Verificar configuración de base de datos
3. Confirmar permisos de archivos
4. Revisar configuración de roles y permisos

## Próximas Mejoras

- [ ] Notificaciones en tiempo real
- [ ] Sistema de backup automático
- [ ] API REST para móvil
- [ ] Integración con pagos
- [ ] Sistema de inventario avanzado
- [ ] Métricas y analytics avanzados
