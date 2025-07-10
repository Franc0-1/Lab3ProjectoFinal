import React from 'react';
import { Head, Link } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';

export default function CustomerDashboard({ auth, userOrders = [], recentPizzas = [] }) {
    const pendingOrders = userOrders.filter(order => order.status === 'pending');
    const completedOrders = userOrders.filter(order => order.status === 'delivered');

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Mi Panel</h2>}
        >
            <Head title="Customer Dashboard" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    {/* Bienvenida */}
                    <div className="mb-8">
                        <div className="bg-gradient-to-r from-orange-600 to-red-600 text-white p-6 rounded-lg shadow-lg">
                            <h1 className="text-3xl font-bold mb-2">¡Hola, {auth.user.name}!</h1>
                            <p className="text-orange-100">Bienvenido a LaQueva Pizza - ¡Las mejores pizzas te esperan!</p>
                        </div>
                    </div>

                    {/* Estadísticas del cliente */}
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div className="p-6 bg-gradient-to-r from-blue-500 to-blue-600 text-white">
                                <div className="flex items-center">
                                    <div className="flex-shrink-0">
                                        <svg className="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <div className="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt className="text-sm font-medium truncate">Total Pedidos</dt>
                                            <dd className="text-lg font-medium">{userOrders.length}</dd>
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
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div className="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt className="text-sm font-medium truncate">Pedidos Pendientes</dt>
                                            <dd className="text-lg font-medium">{pendingOrders.length}</dd>
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
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div className="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt className="text-sm font-medium truncate">Pedidos Completados</dt>
                                            <dd className="text-lg font-medium">{completedOrders.length}</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Acciones rápidas */}
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                        <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div className="p-6">
                                <h3 className="text-lg font-medium text-gray-900 mb-4">Realizar Pedido</h3>
                                <div className="space-y-2">
                                    <Link
                                        href="/"
                                        className="block w-full text-center bg-red-600 text-white py-3 px-4 rounded-md hover:bg-red-700 transition duration-200 font-medium"
                                    >
                                        🍕 Ver Menú Completo
                                    </Link>
                                    <Link
                                        href="/cart"
                                        className="block w-full text-center bg-orange-600 text-white py-2 px-4 rounded-md hover:bg-orange-700 transition duration-200"
                                    >
                                        Ver Carrito
                                    </Link>
                                </div>
                            </div>
                        </div>

                        <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div className="p-6">
                                <h3 className="text-lg font-medium text-gray-900 mb-4">Mis Pedidos</h3>
                                <div className="space-y-2">
                                    <Link
                                        href="/my-orders"
                                        className="block w-full text-center bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition duration-200"
                                    >
                                        Ver Todos mis Pedidos
                                    </Link>
                                    <Link
                                        href="/order-history"
                                        className="block w-full text-center bg-purple-600 text-white py-2 px-4 rounded-md hover:bg-purple-700 transition duration-200"
                                    >
                                        Historial de Pedidos
                                    </Link>
                                </div>
                            </div>
                        </div>

                        <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div className="p-6">
                                <h3 className="text-lg font-medium text-gray-900 mb-4">Mi Cuenta</h3>
                                <div className="space-y-2">
                                    <Link
                                        href={route('profile.edit')}
                                        className="block w-full text-center bg-gray-600 text-white py-2 px-4 rounded-md hover:bg-gray-700 transition duration-200"
                                    >
                                        Editar Perfil
                                    </Link>
                                    <Link
                                        href="/favorites"
                                        className="block w-full text-center bg-pink-600 text-white py-2 px-4 rounded-md hover:bg-pink-700 transition duration-200"
                                    >
                                        Mis Favoritos
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Pedidos recientes */}
                    <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div className="p-6">
                                <h3 className="text-lg font-medium text-gray-900 mb-4">Pedidos Recientes</h3>
                                <div className="space-y-3">
                                    {userOrders.slice(0, 5).length > 0 ? (
                                        userOrders.slice(0, 5).map((order, index) => (
                                            <div key={index} className="flex items-center justify-between p-3 bg-gray-50 rounded-md">
                                                <div>
                                                    <p className="text-sm font-medium text-gray-900">Pedido #{order.id}</p>
                                                    <p className="text-sm text-gray-500">
                                                        {order.status === 'pending' ? 'Pendiente' : 
                                                         order.status === 'preparing' ? 'Preparando' :
                                                         order.status === 'ready' ? 'Listo' :
                                                         order.status === 'delivered' ? 'Entregado' : 'Cancelado'}
                                                    </p>
                                                </div>
                                                <div className="text-right">
                                                    <p className="text-sm font-medium text-gray-900">${order.total_amount}</p>
                                                    <p className="text-sm text-gray-500">{new Date(order.created_at).toLocaleDateString()}</p>
                                                </div>
                                            </div>
                                        ))
                                    ) : (
                                        <div className="text-center py-8">
                                            <p className="text-gray-500">No tienes pedidos aún</p>
                                            <Link
                                                href="/"
                                                className="inline-block mt-2 text-red-600 hover:text-red-700 font-medium"
                                            >
                                                ¡Haz tu primer pedido!
                                            </Link>
                                        </div>
                                    )}
                                </div>
                            </div>
                        </div>

                        <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div className="p-6">
                                <h3 className="text-lg font-medium text-gray-900 mb-4">Pizzas Recomendadas</h3>
                                <div className="space-y-3">
                                    {recentPizzas.length > 0 ? (
                                        recentPizzas.map((pizza, index) => (
                                            <div key={index} className="flex items-center justify-between p-3 bg-gray-50 rounded-md">
                                                <div>
                                                    <p className="text-sm font-medium text-gray-900">{pizza.name}</p>
                                                    <p className="text-sm text-gray-500">{pizza.description}</p>
                                                </div>
                                                <div className="text-right">
                                                    <p className="text-sm font-medium text-gray-900">${pizza.price}</p>
                                                    <Link
                                                        href={`/pizza/${pizza.id}`}
                                                        className="text-sm text-red-600 hover:text-red-700"
                                                    >
                                                        Ver más
                                                    </Link>
                                                </div>
                                            </div>
                                        ))
                                    ) : (
                                        <div className="text-center py-8">
                                            <p className="text-gray-500">Descubre nuestras deliciosas pizzas</p>
                                            <Link
                                                href="/"
                                                className="inline-block mt-2 text-red-600 hover:text-red-700 font-medium"
                                            >
                                                Ver menú completo
                                            </Link>
                                        </div>
                                    )}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
