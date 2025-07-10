import React from 'react';
import { Head, Link } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';

export default function PizzasShow({ auth, pizza }) {
    // Helper function to safely call route
    const safeRoute = (routeName, params = {}) => {
        try {
            const routeResult = route(routeName, params);
            if (!routeResult) {
                console.error(`Route '${routeName}' returned null`);
                return '#';
            }
            return routeResult;
        } catch (error) {
            console.error(`Error calling route '${routeName}':`, error);
            return '#';
        }
    };

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Detalles de Pizza</h2>}
        >
            <Head title={`Pizza: ${pizza.name}`} />

            <div className="py-12">
                <div className="max-w-4xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900">
                            {/* Header */}
                            <div className="flex justify-between items-center mb-6">
                                <h1 className="text-3xl font-bold text-gray-900">{pizza.name}</h1>
                                <div className="flex space-x-3">
                                    <Link
                                        href={safeRoute('pizzas.edit', pizza.id)}
                                        className="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200"
                                    >
                                        ✏️ Editar
                                    </Link>
                                    <Link
                                        href={safeRoute('pizzas.index')}
                                        className="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200"
                                    >
                                        ← Volver
                                    </Link>
                                </div>
                            </div>

                            {/* Content Grid */}
                            <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                {/* Imagen */}
                                <div className="space-y-6">
                                    <div className="aspect-square w-full max-w-md mx-auto">
                                        <img
                                            src={pizza.image || '/assets/pizzas/pizza-4.webp'}
                                            alt={pizza.name}
                                            className="w-full h-full object-cover rounded-xl shadow-lg"
                                            onError={(e) => {
                                                e.target.src = '/assets/pizzas/pizza-4.webp';
                                            }}
                                        />
                                    </div>
                                    
                                    {/* Estado y destacada */}
                                    <div className="flex justify-center space-x-4">
                                        <span className={`px-4 py-2 text-sm font-semibold rounded-full ${
                                            pizza.available 
                                                ? 'bg-green-100 text-green-800' 
                                                : 'bg-red-100 text-red-800'
                                        }`}>
                                            {pizza.available ? '✅ Disponible' : '❌ No disponible'}
                                        </span>
                                        
                                        {pizza.featured && (
                                            <span className="px-4 py-2 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                ⭐ Destacada
                                            </span>
                                        )}
                                    </div>
                                </div>

                                {/* Información */}
                                <div className="space-y-6">
                                    {/* Información básica */}
                                    <div className="bg-gray-50 p-6 rounded-lg">
                                        <h3 className="text-lg font-semibold text-gray-900 mb-4">Información Básica</h3>
                                        <div className="space-y-3">
                                            <div className="flex justify-between">
                                                <span className="font-medium text-gray-600">Categoría:</span>
                                                <span className="px-2 py-1 text-sm bg-blue-100 text-blue-800 rounded-full">
                                                    {pizza.category?.name || 'Sin categoría'}
                                                </span>
                                            </div>
                                            <div className="flex justify-between">
                                                <span className="font-medium text-gray-600">Precio:</span>
                                                <span className="text-2xl font-bold text-red-600">
                                                    ${pizza.price ? Number(pizza.price).toLocaleString() : '0'}
                                                </span>
                                            </div>
                                            <div className="flex justify-between">
                                                <span className="font-medium text-gray-600">Tiempo de preparación:</span>
                                                <span className="font-semibold">
                                                    {pizza.preparation_time || 15} minutos
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    {/* Descripción */}
                                    {pizza.description && (
                                        <div className="bg-gray-50 p-6 rounded-lg">
                                            <h3 className="text-lg font-semibold text-gray-900 mb-3">Descripción</h3>
                                            <p className="text-gray-700 leading-relaxed">
                                                {pizza.description}
                                            </p>
                                        </div>
                                    )}

                                    {/* Ingredientes */}
                                    {pizza.ingredients && pizza.ingredients.length > 0 && (
                                        <div className="bg-gray-50 p-6 rounded-lg">
                                            <h3 className="text-lg font-semibold text-gray-900 mb-4">Ingredientes</h3>
                                            <div className="flex flex-wrap gap-2">
                                                {pizza.ingredients.map((ingredient, index) => (
                                                    <span
                                                        key={index}
                                                        className="px-3 py-1 bg-red-100 text-red-800 text-sm rounded-full"
                                                    >
                                                        {ingredient.trim()}
                                                    </span>
                                                ))}
                                            </div>
                                        </div>
                                    )}

                                    {/* Información adicional */}
                                    <div className="bg-gray-50 p-6 rounded-lg">
                                        <h3 className="text-lg font-semibold text-gray-900 mb-4">Información Adicional</h3>
                                        <div className="space-y-2 text-sm text-gray-600">
                                            <div className="flex justify-between">
                                                <span>ID:</span>
                                                <span className="font-mono">#{pizza.id}</span>
                                            </div>
                                            <div className="flex justify-between">
                                                <span>Creado:</span>
                                                <span>{new Date(pizza.created_at).toLocaleDateString('es-ES')}</span>
                                            </div>
                                            <div className="flex justify-between">
                                                <span>Última actualización:</span>
                                                <span>{new Date(pizza.updated_at).toLocaleDateString('es-ES')}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {/* Acciones inferiores */}
                            <div className="mt-8 pt-6 border-t border-gray-200">
                                <div className="flex justify-between items-center">
                                    <div className="text-sm text-gray-500">
                                        Esta pizza {pizza.available ? 'está disponible' : 'no está disponible'} para pedidos
                                    </div>
                                    <div className="flex space-x-3">
                                        <Link
                                            href={safeRoute('pizzas.edit', pizza.id)}
                                            className="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-6 rounded-lg transition duration-200"
                                        >
                                            Editar Pizza
                                        </Link>
                                        <Link
                                            href={safeRoute('pizzas.create')}
                                            className="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-lg transition duration-200"
                                        >
                                            + Nueva Pizza
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
