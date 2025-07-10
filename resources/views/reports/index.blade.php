@extends('layouts.app')
@section('title', 'Reportes')
@section('content')

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6">
            <h1 class="text-2xl font-bold leading-tight text-gray-900">
                📊 Centro de Reportes
            </h1>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                Genera y descarga reportes de tu pizzería
            </p>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                            <span class="text-white font-bold text-sm">🍕</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Pizzas</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['total_pizzas'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                            <span class="text-white font-bold text-sm">👥</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Clientes</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['total_customers'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                            <span class="text-white font-bold text-sm">📝</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Órdenes</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['total_orders'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                            <span class="text-white font-bold text-sm">🏷️</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Categorías</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['total_categories'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reportes por Categoría -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Reportes de Pizzas -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">🍕 Reportes de Pizzas</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Exporta información de las pizzas</p>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <div class="space-y-4">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('reports.pizzas.excel') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            📊 Exportar Excel
                        </a>
                        <a href="{{ route('reports.pizzas.pdf') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            📄 Exportar PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reportes de Clientes -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">👥 Reportes de Clientes</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Exporta información de los clientes</p>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <div class="space-y-4">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('reports.customers.excel') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            📊 Exportar Excel
                        </a>
                        <a href="{{ route('reports.customers.pdf') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            📄 Exportar PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reportes de Órdenes -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">📝 Reportes de Órdenes</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Exporta información de las órdenes</p>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <div class="space-y-4">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('reports.orders.excel') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            📊 Exportar Excel
                        </a>
                        <a href="{{ route('reports.orders.pdf') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            📄 Exportar PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reportes de Categorías -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">🏷️ Reportes de Categorías</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Exporta información de las categorías</p>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <div class="space-y-4">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('reports.categories.excel') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            📊 Exportar Excel
                        </a>
                        <a href="{{ route('reports.categories.pdf') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            📄 Exportar PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reporte General -->
    <div class="mt-6 bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">📋 Reporte General</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Exporta un reporte completo con toda la información</p>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('reports.general.pdf') }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    📄 Descargar Reporte General (PDF)
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
