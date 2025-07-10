@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Pizzas -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-red-100 text-red-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-700">Pizzas</h3>
                <p class="text-3xl font-bold text-gray-900">{{ $stats['totalPizzas'] }}</p>
            </div>
        </div>
    </div>

    <!-- Total Clientes -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-700">Clientes</h3>
                <p class="text-3xl font-bold text-gray-900">{{ $stats['totalCustomers'] }}</p>
            </div>
        </div>
    </div>

    <!-- Total Pedidos -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-700">Pedidos</h3>
                <p class="text-3xl font-bold text-gray-900">{{ $stats['totalOrders'] }}</p>
            </div>
        </div>
    </div>

    <!-- Total Ingresos -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                </svg>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-700">Ingresos</h3>
                <p class="text-3xl font-bold text-gray-900">${{ number_format($stats['totalRevenue']) }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Acciones Rápidas -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Gestión de Pizzas</h3>
        <p class="text-gray-600 mb-4">Administra el menú de pizzas, precios y disponibilidad.</p>
        <div class="flex space-x-2">
            <a href="{{ route('pizzas.index') }}" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                Ver Pizzas
            </a>
            <a href="{{ route('pizzas.create') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Agregar Pizza
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Gestión de Clientes</h3>
        <p class="text-gray-600 mb-4">Administra la información de los clientes registrados.</p>
        <div class="flex space-x-2">
            <a href="{{ route('customers.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Ver Clientes
            </a>
            <a href="{{ route('customers.create') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Agregar Cliente
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Reportes y Análisis</h3>
        <p class="text-gray-600 mb-4">Genera reportes en PDF y Excel para análisis de datos.</p>
        <div class="flex space-x-2">
            <a href="{{ route('reports.index') }}" class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
                Ver Reportes
            </a>
        </div>
    </div>
</div>

<!-- Distribución de Usuarios -->
<div class="bg-white rounded-lg shadow p-6">
    <h3 class="text-lg font-semibold text-gray-700 mb-4">Distribución de Usuarios</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="text-center">
            <div class="text-2xl font-bold text-red-600">{{ $stats['adminUsers'] }}</div>
            <div class="text-sm text-gray-600">Administradores</div>
        </div>
        <div class="text-center">
            <div class="text-2xl font-bold text-blue-600">{{ $stats['employeeUsers'] }}</div>
            <div class="text-sm text-gray-600">Empleados</div>
        </div>
        <div class="text-center">
            <div class="text-2xl font-bold text-green-600">{{ $stats['customerUsers'] }}</div>
            <div class="text-sm text-gray-600">Clientes</div>
        </div>
    </div>
</div>
@endsection
