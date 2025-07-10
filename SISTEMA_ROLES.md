# Sistema de Roles y Autenticación - LaQueva Pizza

## Resumen del Sistema

El sistema de LaQueva Pizza implementa un sistema de roles y permisos robusto que diferencia entre tres tipos de usuarios:

- **Admin**: Acceso completo al sistema
- **Employee**: Acceso limitado a funciones operativas
- **Customer**: Acceso básico para clientes

## Estructura del Sistema

### 1. Modelos

#### `User` (app/Models/User.php)
- Modelo principal para autenticación
- Utiliza el trait `HasRoles` de Spatie Permission
- Campos: `name`, `email`, `password`
- Maneja roles y permisos

#### `Customer` (app/Models/Customer.php)
- Modelo separado para datos adicionales de clientes
- Campos: `name`, `phone`, `email`, `address`, `neighborhood`, `latitude`, `longitude`, `frequent_customer`
- **Importante**: Este modelo NO está relacionado con `User` actualmente

### 2. Roles y Permisos

#### Roles Disponibles:
- `admin`: Administrador del sistema
- `employee`: Empleado de la pizzería
- `customer`: Cliente registrado

#### Permisos (definidos en UserSeeder):
- `view_dashboard`: Ver panel de administración
- `manage_orders`: Gestionar pedidos
- `manage_pizzas`: Gestionar pizzas
- `manage_categories`: Gestionar categorías
- `manage_customers`: Gestionar clientes
- `manage_users`: Gestionar usuarios
- `manage_promotions`: Gestionar promociones
- `export_reports`: Exportar reportes
- `view_reports`: Ver reportes
- `manage_settings`: Gestionar configuraciones

#### Asignación de Permisos:
- **Admin**: Todos los permisos
- **Employee**: `view_dashboard`, `manage_orders`, `manage_pizzas`, `manage_categories`, `manage_customers`, `view_reports`
- **Customer**: `view_dashboard` (limitado)

### 3. Flujo de Registro

#### Registro de Usuarios (RegisteredUserController):
1. Usuario completa formulario de registro
2. Se validan los datos
3. Se crea el usuario en la tabla `users`
4. **Automáticamente se asigna rol `customer`**
5. Se inicia sesión automáticamente
6. Redirección según rol:
   - Admin/Employee → `/dashboard`
   - Customer → `/` (página principal)

### 4. Flujo de Login

#### Login (AuthenticatedSessionController):
1. Usuario ingresa credenciales
2. Se autentica el usuario
3. Redirección según rol:
   - Admin/Employee → `/dashboard`
   - Customer → `/` (página principal)

### 5. Protección de Rutas

#### Middleware `AdminMiddleware`:
- Protege rutas administrativas
- Verifica que el usuario tenga rol `admin` o `employee`
- Redirige a clientes a la página principal

#### Rutas Protegidas:
- `/dashboard` - Solo admin/employee
- `/pizzas/*` - Solo admin/employee
- `/customers/*` - Solo admin/employee
- `/orders/*` - Solo admin/employee
- `/reports/*` - Solo admin/employee
- `/profile/*` - Solo admin/employee

#### Rutas Públicas:
- `/` - Página principal (todos)
- `/cart/*` - Carrito de compras (todos)
- `/login` - Login (no autenticados)
- `/register` - Registro (no autenticados)

### 6. Dashboards Diferenciados

#### Dashboard Admin/Employee (`/dashboard`):
- Estadísticas del negocio
- Gestión de pizzas, pedidos, clientes
- Reportes y exportaciones
- Acceso completo al sistema

#### Dashboard Customer (`/customer-dashboard`):
- Estadísticas personales del cliente
- Historial de pedidos
- Acceso al carrito
- Perfil personal

### 7. Usuarios Predefinidos (Seeder)

El sistema incluye usuarios de prueba:

```php
// Admin
Email: admin@laqueva.com
Password: password
Rol: admin

// Employee  
Email: empleado@laqueva.com
Password: password
Rol: employee

// Customer
Email: cliente@laqueva.com
Password: password
Rol: customer
```

### 8. Cómo Crear Usuarios

#### Manualmente (para admin/employee):
```php
$user = User::create([
    'name' => 'Nombre',
    'email' => 'email@ejemplo.com',
    'password' => Hash::make('contraseña')
]);

$user->assignRole('admin'); // o 'employee'
```

#### Automáticamente (registro público):
Los usuarios que se registren públicamente reciben automáticamente el rol `customer`.

## Diferencias Clave

### Usuario vs Cliente:
- **Usuario (`User`)**: Entidad de autenticación con roles
- **Cliente (`Customer`)**: Datos adicionales para clientes de la pizzería
- **Importante**: No hay relación directa entre ambos modelos actualmente

### Acceso al Sistema:
- **Página Principal (/)**: Todos pueden acceder
- **Dashboard Admin**: Solo admin/employee
- **Funciones de Gestión**: Solo admin/employee
- **Carrito**: Todos pueden usar
- **Pedidos**: Los clientes pueden hacer pedidos, admin/employee pueden gestionarlos

### Navegación:
- **Usuarios autenticados con rol admin/employee**: Acceso al dashboard completo
- **Usuarios autenticados con rol customer**: Permanecen en la página principal con opciones de cliente
- **Usuarios no autenticados**: Solo pueden ver el menú y usar el carrito

## Configuración y Comandos

### Ejecutar Seeders:
```bash
php artisan db:seed --class=UserSeeder
```

### Crear Roles Manualmente:
```bash
php artisan tinker
Role::create(['name' => 'admin']);
Role::create(['name' => 'employee']);
Role::create(['name' => 'customer']);
```

### Verificar Roles de Usuario:
```bash
php artisan tinker
$user = User::find(1);
echo $user->getRoleNames(); // Muestra los roles del usuario
```

## Seguridad

- Las rutas administrativas están protegidas por middleware
- Los permisos se verifican a nivel de controlador
- El sistema usa Laravel's built-in authentication
- Spatie Permission proporciona gestión avanzada de roles

## Extensibilidad

Para agregar nuevos roles o permisos:
1. Actualizar `UserSeeder.php`
2. Ejecutar `php artisan db:seed --class=UserSeeder`
3. Actualizar middleware si es necesario
4. Actualizar interfaces de usuario según roles
