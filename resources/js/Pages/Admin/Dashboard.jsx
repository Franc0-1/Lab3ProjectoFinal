import React from 'react';
import { Head, Link } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';

export default function AdminDashboard({ auth, stats = {} }) {
    const {
        totalPizzas = 0,
        totalOrders = 0,
        totalCustomers = 0,
        totalRevenue = 0,
        recentOrders = [],
        popularPizzas = []
    } = stats;

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Panel de Administración</h2>}
        >
            <Head title="Admin Dashboard" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    {/* Bienvenida */}
                    <div className="mb-8">
                        <div className="bg-gradient-to-r from-red-600 to-orange-600 text-white p-6 rounded-lg shadow-lg">
                            <h1 className="text-3xl font-bold mb-2">¡Bienvenido, {auth.user.name}!</h1>
                            <p className="text-red-100">Panel de administración de LaQueva Pizza</p>
                        </div>
                    </div>

                    {/* Cards de estadísticas */}
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div className="p-6 bg-gradient-to-r from-blue-500 to-blue-600 text-white">
                                <div className="flex items-center">
                                    <div className="flex-shrink-0">
                                        <svg className="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                        </svg>
                                    </div>
                                    <div className="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt className="text-sm font-medium truncate">Total Pizzas</dt>
                                            <dd className="text-lg font-medium">{totalPizzas}</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div className="p-6 bg-gradient-to-r from-green-500 to-green-600 text-white">
                                <div className="flex items-center">
                                    <div className="flex-shrink-0">
                                        <svg className="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <div className="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt className="text-sm font-medium truncate">Total Pedidos</dt>
                                            <dd className="text-lg font-medium">{totalOrders}</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div className="p-6 bg-gradient-to-r from-purple-500 to-purple-600 text-white">
                                <div className="flex items-center">
                                    <div className="flex-shrink-0">
                                        <svg className="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </div>
                                    <div className="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt className="text-sm font-medium truncate">Total Clientes</dt>
                                            <dd className="text-lg font-medium">{totalCustomers}</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div className="p-6 bg-gradient-to-r from-yellow-500 to-yellow-600 text-white">
                                <div className="flex items-center">
                                    <div className="flex-shrink-0">
                                        <svg className="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                        </svg>
                                    </div>
                                    <div className="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt className="text-sm font-medium truncate">Ingresos Totales</dt>
                                            <dd className="text-lg font-medium">${totalRevenue.toLocaleString()}</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Acciones administrativas */}
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                        <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div className="p-6">
                                <h3 className="text-lg font-medium text-gray-900 mb-4">Gestión de Pizzas</h3>
                                <div className="space-y-2">
                                    <Link
                                        href={route('pizzas.index')}
                                        className="block w-full text-center bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition duration-200"
                                    >
                                        Ver Todas las Pizzas
                                    </Link>
                                    <Link
                                        href={route('pizzas.create')}
                                        className="block w-full text-center bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 transition duration-200"
                                    >
                                        Crear Nueva Pizza
                                    </Link>
                                </div>
                            </div>
                        </div>

                        <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div className="p-6">
                                <h3 className="text-lg font-medium text-gray-900 mb-4">Gestión de Pedidos</h3>
                                <div className="space-y-2">
                                    <Link
                                        href={route('orders.index')}
                                        className="block w-full text-center bg-purple-600 text-white py-2 px-4 rounded-md hover:bg-purple-700 transition duration-200"
                                    >
                                        Ver Todos los Pedidos
                                    </Link>
                                    <Link
                                        href={route('orders.create')}
                                        className="block w-full text-center bg-orange-600 text-white py-2 px-4 rounded-md hover:bg-orange-700 transition duration-200"
                                    >
                                        Crear Nuevo Pedido
                                    </Link>
                                </div>
                            </div>
                        </div>

                        <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div className="p-6">
                                <h3 className="text-lg font-medium text-gray-900 mb-4">Gestión de Clientes</h3>
                                <div className="space-y-2">
                                    <Link
                                        href={route('customers.index')}
                                        className="block w-full text-center bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 transition duration-200"
                                    >
                                        Ver Todos los Clientes
                                    </Link>
                                    <Link
                                        href={route('customers.create')}
                                        className="block w-full text-center bg-pink-600 text-white py-2 px-4 rounded-md hover:bg-pink-700 transition duration-200"
                                    >
                                        Agregar Cliente
                                    </Link>
                                </div>
                            </div>
                        </div>

                        <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div className="p-6">
                                <h3 className="text-lg font-medium text-gray-900 mb-4">Reportes y Análisis</h3>
                                <div className="space-y-2">
                                    <Link
                                        href={route('reports.index')}
                                        className="block w-full text-center bg-gray-600 text-white py-2 px-4 rounded-md hover:bg-gray-700 transition duration-200"
                                    >
                                        Ver Reportes
                                    </Link>
                                    <a
                                        href={route('reports.orders.excel')}
                                        className="block w-full text-center bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 transition duration-200"
                                    >
                                        Exportar Excel
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div className="p-6">
                                <h3 className="text-lg font-medium text-gray-900 mb-4">Configuración</h3>
                                <div className="space-y-2">
                                    <Link
                                        href={route('profile.edit')}
                                        className="block w-full text-center bg-yellow-600 text-white py-2 px-4 rounded-md hover:bg-yellow-700 transition duration-200"
                                    >
                                        Editar Perfil
                                    </Link>
                                    <Link
                                        href="/"
                                        className="block w-full text-center bg-teal-600 text-white py-2 px-4 rounded-md hover:bg-teal-700 transition duration-200"
                                    >
                                        Ver Sitio Web
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
