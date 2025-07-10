<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PizzaController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DashboardController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Inertia\Inertia;

// Ruta principal
Route::get('/', function () {
    return view('home');
})->name('welcome');

// Ruta alternativa para welcome
Route::get('/welcome', function () {
    return view('home');
})->name('home');

// Rutas de reportes (protegidas por admin)
Route::prefix('reports')->name('reports.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [ReportController::class, 'index'])->name('index');
    
    // Exportaciones Excel
    Route::get('/pizzas/excel', [ReportController::class, 'exportPizzasExcel'])->name('pizzas.excel');
    Route::get('/customers/excel', [ReportController::class, 'exportCustomersExcel'])->name('customers.excel');
    Route::get('/categories/excel', [ReportController::class, 'exportCategoriesExcel'])->name('categories.excel');
    Route::get('/orders/excel', [ReportController::class, 'exportOrdersExcel'])->name('orders.excel');
    
    // Exportaciones PDF
    Route::get('/pizzas/pdf', [ReportController::class, 'exportPizzasPdf'])->name('pizzas.pdf');
    Route::get('/customers/pdf', [ReportController::class, 'exportCustomersPdf'])->name('customers.pdf');
    Route::get('/categories/pdf', [ReportController::class, 'exportCategoriesPdf'])->name('categories.pdf');
    Route::get('/orders/pdf', [ReportController::class, 'exportOrdersPdf'])->name('orders.pdf');
    Route::get('/general/pdf', [ReportController::class, 'exportGeneralPdf'])->name('general.pdf');
    
    // Nuevos reportes
    Route::get('/sales/pdf', [ReportController::class, 'exportSalesReport'])->name('sales.pdf');
    Route::get('/frequent-customers/pdf', [ReportController::class, 'exportFrequentCustomers'])->name('frequent-customers.pdf');
});

// Rutas legacy para compatibilidad
Route::get('/export-excel', [ReportController::class, 'exportExcel'])->name('export.excel');
Route::get('/export-pdf', [ReportController::class, 'exportPdf'])->name('export.pdf');

// Rutas del carrito (públicas)
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::patch('/update/{id}', [CartController::class, 'update'])->name('update');
    Route::delete('/remove/{id}', [CartController::class, 'remove'])->name('remove');
    Route::delete('/delete/{id}', [CartController::class, 'delete'])->name('delete');
    Route::delete('/clear', [CartController::class, 'clear'])->name('clear');
    Route::get('/count', [CartController::class, 'count'])->name('count');
    Route::get('/debug', [CartController::class, 'debug'])->name('debug');
    Route::get('/test', function () {
        return view('test-cart-buttons');
    })->name('test');
    
    Route::get('/test-csrf-cart', function () {
        return view('test-csrf-cart');
    });
});

// Rutas de Inertia (mantenemos las originales)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('dashboard');

// Rutas de administración
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard de administración
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Gestión de pizzas
    Route::resource('pizzas', App\Http\Controllers\Admin\PizzaController::class);
    Route::patch('pizzas/{pizza}/toggle-availability', [App\Http\Controllers\Admin\PizzaController::class, 'toggleAvailability'])->name('pizzas.toggle-availability');
    
    // Gestión de órdenes
    Route::resource('orders', App\Http\Controllers\Admin\OrderController::class);
    Route::patch('orders/{order}/status', [App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::get('orders-statistics', [App\Http\Controllers\Admin\OrderController::class, 'statistics'])->name('orders.statistics');
    
    // Gestión de usuarios
    Route::get('users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::patch('users/{user}/role', [App\Http\Controllers\Admin\UserController::class, 'updateRole'])->name('users.update-role');
    Route::get('users-statistics', [App\Http\Controllers\Admin\UserController::class, 'statistics'])->name('users.statistics');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Rutas de recursos principales (protegidas por admin)
    Route::resource('pizzas', PizzaController::class);
    Route::patch('pizzas/{pizza}/toggle-availability', [PizzaController::class, 'toggleAvailability'])->name('pizzas.toggle-availability');
    Route::resource('customers', CustomerController::class);
    Route::post('customers/{customer}/toggle-frequent', [CustomerController::class, 'toggleFrequent'])->name('customers.toggle-frequent');
    Route::post('customers/bulk-action', [CustomerController::class, 'bulkAction'])->name('customers.bulk-action');
    Route::resource('orders', OrderController::class);
});

// Rutas temporales para probar autenticación
Route::get('/test-login', [App\Http\Controllers\TestAuthController::class, 'testLogin'])->name('test.login');
Route::get('/test-register', [App\Http\Controllers\TestAuthController::class, 'testRegister'])->name('test.register');

// Rutas temporales sin middleware guest
Route::get('/direct-login', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'create'])->name('direct.login');
Route::get('/direct-register', [App\Http\Controllers\Auth\RegisteredUserController::class, 'create'])->name('direct.register');

// Rutas de reemplazo para login y register sin middleware guest
Route::get('login', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('login', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'store']);
Route::get('register', [App\Http\Controllers\Auth\RegisteredUserController::class, 'create'])->name('register');
Route::post('register', [App\Http\Controllers\Auth\RegisteredUserController::class, 'store']);

// Rutas simples de autenticación (sin dependencias JS)
Route::get('simple-login', [App\Http\Controllers\SimpleAuthController::class, 'showLoginForm'])->name('simple.login');
Route::post('simple-login', [App\Http\Controllers\SimpleAuthController::class, 'login']);
Route::get('simple-register', [App\Http\Controllers\SimpleAuthController::class, 'showRegisterForm'])->name('simple.register');
Route::post('simple-register', [App\Http\Controllers\SimpleAuthController::class, 'register']);

// Rutas de password reset sin middleware guest
Route::get('forgot-password', [App\Http\Controllers\Auth\PasswordResetLinkController::class, 'create'])->name('password.request');
Route::post('forgot-password', [App\Http\Controllers\Auth\PasswordResetLinkController::class, 'store'])->name('password.email');
Route::get('reset-password/{token}', [App\Http\Controllers\Auth\NewPasswordController::class, 'create'])->name('password.reset');
Route::post('reset-password', [App\Http\Controllers\Auth\NewPasswordController::class, 'store'])->name('password.store');

// Ruta de diagnóstico
Route::get('test-forgot-link', function () {
    return view('test-forgot-link');
})->name('test.forgot.link');

// Rutas que requieren autenticación
Route::middleware('auth')->group(function () {
    Route::post('logout', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])->name('logout');
    
    // Rutas del perfil
    Route::get('profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.update.password');
    Route::delete('profile', [App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rutas de prueba para CSRF
Route::get('/test-csrf', function () {
    return response()->json([
        'csrf_token' => csrf_token(),
        'session_id' => session()->getId(),
        'session_token' => session()->token(),
        'meta_token' => csrf_token()
    ]);
});

// Ruta de prueba POST para CSRF
Route::post('/test-csrf-post', function (Request $request) {
    return response()->json([
        'success' => true,
        'message' => 'CSRF validation passed',
        'received_data' => $request->all(),
        'csrf_token' => csrf_token()
    ]);
});

// Ruta de diagnóstico de sesiones
Route::get('/test-session', function () {
    // Crear una sesión de prueba
    session(['test_key' => 'test_value']);
    session()->save();
    
    // Verificar que se guardó
    $sessionData = DB::table('sessions')
        ->where('id', session()->getId())
        ->first();
    
    return response()->json([
        'session_id' => session()->getId(),
        'session_driver' => config('session.driver'),
        'session_table_exists' => Schema::hasTable('sessions'),
        'session_in_db' => $sessionData ? true : false,
        'session_data' => session()->all(),
        'csrf_token' => csrf_token(),
        'db_session_count' => DB::table('sessions')->count()
    ]);
});

// Ruta de prueba para clientes
Route::get('/test-customers', function () {
    try {
        $customers = \App\Models\Customer::with('orders')->paginate(15);
        return response()->json([
            'success' => true,
            'customers_count' => $customers->total(),
            'customers' => $customers->items(),
            'message' => 'Customers loaded successfully'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});

// Rutas de prueba para controlador
Route::get('/test-customer-controller', [App\Http\Controllers\TestCustomerController::class, 'index']);
Route::get('/test-customer-inertia', [App\Http\Controllers\TestCustomerController::class, 'inertiaTest']);

Route::post('/test-csrf-post', function (Request $request) {
    return response()->json([
        'status' => 'success',
        'message' => 'CSRF token válido',
        'data' => $request->all()
    ]);
});

// Comentar temporalmente las rutas originales
// require __DIR__.'/auth.php';
