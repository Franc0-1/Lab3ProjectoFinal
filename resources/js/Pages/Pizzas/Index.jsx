import React, { useState } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';

export default function PizzasIndex({ auth, pizzas, categories, flash }) {
    const [searchTerm, setSearchTerm] = useState('');
    const [selectedCategory, setSelectedCategory] = useState('');
    const { delete: destroy, processing } = useForm();

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

    const handleDelete = (id) => {
        if (confirm('¿Estás seguro de que quieres eliminar esta pizza?')) {
            destroy(safeRoute('pizzas.destroy', id));
        }
    };

    const handleToggleAvailability = (id) => {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = safeRoute('pizzas.toggle-availability', id);
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'PATCH';
        
        const tokenField = document.createElement('input');
        tokenField.type = 'hidden';
        tokenField.name = '_token';
        tokenField.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        form.appendChild(methodField);
        form.appendChild(tokenField);
        document.body.appendChild(form);
        form.submit();
    };

    const filteredPizzas = pizzas.filter(pizza => {
        const matchesSearch = pizza.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
                            pizza.description?.toLowerCase().includes(searchTerm.toLowerCase());
        const matchesCategory = selectedCategory === '' || pizza.category_id == selectedCategory;
        return matchesSearch && matchesCategory;
    });

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Gestión de Pizzas</h2>}
        >
            <Head title="Pizzas" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900">
                            {/* Flash Messages */}
                            {flash?.success && (
                                <div className="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                                    <span className="block sm:inline">{flash.success}</span>
                                </div>
                            )}
                            {flash?.error && (
                                <div className="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                                    <span className="block sm:inline">{flash.error}</span>
                                </div>
                            )}
                            
                            {/* Header con botón de crear */}
                            <div className="flex justify-between items-center mb-6">
                                <h1 className="text-2xl font-bold text-gray-900">Pizzas</h1>
                                <Link
                                    href={safeRoute('pizzas.create')}
                                    className="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200"
                                >
                                    + Nueva Pizza
                                </Link>
                            </div>

                            {/* Filtros */}
                            <div className="mb-6 flex flex-col sm:flex-row gap-4">
                                <div className="flex-1">
                                    <input
                                        type="text"
                                        placeholder="Buscar pizzas..."
                                        value={searchTerm}
                                        onChange={(e) => setSearchTerm(e.target.value)}
                                        className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                                    />
                                </div>
                                <div className="sm:w-48">
                                    <select
                                        value={selectedCategory}
                                        onChange={(e) => setSelectedCategory(e.target.value)}
                                        className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                                    >
                                        <option value="">Todas las categorías</option>
                                        {categories.map(category => (
                                            <option key={category.id} value={category.id}>
                                                {category.name}
                                            </option>
                                        ))}
                                    </select>
                                </div>
                            </div>

                            {/* Tabla de pizzas */}
                            <div className="overflow-x-auto">
                                <table className="min-w-full bg-white border border-gray-200">
                                    <thead className="bg-gray-50">
                                        <tr>
                                            <th className="px-6 py-3 border-b text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Imagen
                                            </th>
                                            <th className="px-6 py-3 border-b text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Nombre
                                            </th>
                                            <th className="px-6 py-3 border-b text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Categoría
                                            </th>
                                            <th className="px-6 py-3 border-b text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Precio
                                            </th>
                                            <th className="px-6 py-3 border-b text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Estado
                                            </th>
                                            <th className="px-6 py-3 border-b text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Acciones
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody className="bg-white divide-y divide-gray-200">
                                        {filteredPizzas.map(pizza => (
                                            <tr key={pizza.id} className="hover:bg-gray-50">
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    <img
                                                        src={pizza.image || '/assets/pizzas/pizza-4.webp'}
                                                        alt={pizza.name}
                                                        className="w-16 h-16 object-cover rounded-lg"
                                                    />
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    <div className="text-sm font-medium text-gray-900">{pizza.name}</div>
                                                    <div className="text-sm text-gray-500">{pizza.description}</div>
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    <span className="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                        {pizza.category?.name || 'Sin categoría'}
                                                    </span>
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    ${pizza.price?.toLocaleString() || '0'}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    <span className={`px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${
                                                        pizza.available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                                                    }`}>
                                                        {pizza.available ? 'Disponible' : 'No disponible'}
                                                    </span>
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <div className="flex space-x-2">
                                                        <Link
                                                            href={safeRoute('pizzas.show', pizza.id)}
                                                            className="text-blue-600 hover:text-blue-900"
                                                        >
                                                            Ver
                                                        </Link>
                                                        <Link
                                                            href={safeRoute('pizzas.edit', pizza.id)}
                                                            className="text-yellow-600 hover:text-yellow-900"
                                                        >
                                                            Editar
                                                        </Link>
                                                        <button
                                                            onClick={() => handleToggleAvailability(pizza.id)}
                                                            className={`${pizza.available ? 'text-orange-600 hover:text-orange-900' : 'text-green-600 hover:text-green-900'}`}
                                                        >
                                                            {pizza.available ? 'Desactivar' : 'Activar'}
                                                        </button>
                                                        <button
                                                            onClick={() => handleDelete(pizza.id)}
                                                            disabled={processing}
                                                            className="text-red-600 hover:text-red-900 disabled:opacity-50"
                                                        >
                                                            Eliminar
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        ))}
                                    </tbody>
                                </table>
                                
                                {filteredPizzas.length === 0 && (
                                    <div className="text-center py-8">
                                        <p className="text-gray-500">No se encontraron pizzas</p>
                                    </div>
                                )}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
