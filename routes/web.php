<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PizzaController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HealthController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Inertia\Inertia;

// Diagnóstico del servidor
Route::get('/test-simple', function () {
    return response()->json([
        'status' => 'OK',
        'timestamp' => now(),
        'environment' => config('app.env'),
        'debug' => config('app.debug')
    ]);
});

// Health Check
Route::get('/health', [HealthController::class, 'check'])->name('health.check');

// Ruta principal
Route::get('/', function () {
    try {
        return view('home');
    } catch (Exception $e) {
        return response()->json([
            'error' => 'Error loading home view',
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ], 500);
    }
})->name('welcome');

// Ruta alternativa para welcome
Route::get('/welcome', function () {
    return view('home');
})->name('home');

// Reportes - Solo Admin
Route::prefix('reports')->name('reports.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [ReportController::class, 'index'])->name('index');
    
// Excel
    Route::get('/pizzas/excel', [ReportController::class, 'exportPizzasExcel'])->name('pizzas.excel');
    Route::get('/customers/excel', [ReportController::class, 'exportCustomersExcel'])->name('customers.excel');
    Route::get('/categories/excel', [ReportController::class, 'exportCategoriesExcel'])->name('categories.excel');
    Route::get('/orders/excel', [ReportController::class, 'exportOrdersExcel'])->name('orders.excel');
    
// PDF
    Route::get('/pizzas/pdf', [ReportController::class, 'exportPizzasPdf'])->name('pizzas.pdf');
    Route::get('/customers/pdf', [ReportController::class, 'exportCustomersPdf'])->name('customers.pdf');
    Route::get('/categories/pdf', [ReportController::class, 'exportCategoriesPdf'])->name('categories.pdf');
    Route::get('/orders/pdf', [ReportController::class, 'exportOrdersPdf'])->name('orders.pdf');
    Route::get('/general/pdf', [ReportController::class, 'exportGeneralPdf'])->name('general.pdf');
    
// Reportes adicionales
    Route::get('/sales/pdf', [ReportController::class, 'exportSalesReport'])->name('sales.pdf');
    Route::get('/frequent-customers/pdf', [ReportController::class, 'exportFrequentCustomers'])->name('frequent-customers.pdf');
});

// Compatibilidad (deprecated)
Route::get('/export-excel', [ReportController::class, 'exportExcel'])->name('export.excel');
Route::get('/export-pdf', [ReportController::class, 'exportPdf'])->name('export.pdf');

// Carrito - Público
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::patch('/update/{id}', [CartController::class, 'update'])->name('update');
    Route::delete('/remove/{id}', [CartController::class, 'remove'])->name('remove');
    Route::delete('/delete/{id}', [CartController::class, 'delete'])->name('delete');
    Route::delete('/clear', [CartController::class, 'clear'])->name('clear');
    Route::get('/count', [CartController::class, 'count'])->name('count');
    Route::get('/debug', [CartController::class, 'debug'])->name('debug');
});

// Dashboard Inertia
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('dashboard');

// Admin - Panel
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Pizzas
    Route::resource('pizzas', App\Http\Controllers\Admin\PizzaController::class);
    Route::patch('pizzas/{pizza}/toggle-availability', [App\Http\Controllers\Admin\PizzaController::class, 'toggleAvailability'])->name('pizzas.toggle-availability');
    
    // Órdenes
    Route::resource('orders', App\Http\Controllers\Admin\OrderController::class);
    Route::patch('orders/{order}/status', [App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::get('orders-statistics', [App\Http\Controllers\Admin\OrderController::class, 'statistics'])->name('orders.statistics');
    
    // Usuarios
    Route::get('users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::patch('users/{user}/role', [App\Http\Controllers\Admin\UserController::class, 'updateRole'])->name('users.update-role');
    Route::get('users-statistics', [App\Http\Controllers\Admin\UserController::class, 'statistics'])->name('users.statistics');
});


// Autenticación
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

// Perfil de usuario
Route::middleware('auth')->group(function () {
    Route::post('logout', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])->name('logout');
    
    // Rutas del perfil
    Route::get('profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.update.password');
    Route::delete('profile', [App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
});
