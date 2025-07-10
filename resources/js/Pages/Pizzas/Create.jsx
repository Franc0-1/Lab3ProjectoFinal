import React from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';

export default function PizzasCreate({ auth, categories }) {
    const { data, setData, post, processing, errors } = useForm({
        name: '',
        category_id: '',
        description: '',
        price: '',
        image: '',
        ingredients: [],
        available: true,
        featured: false,
        preparation_time: 15,
    });

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

    const handleSubmit = (e) => {
        e.preventDefault();
        post(safeRoute('pizzas.store'));
    };

    const handleIngredientChange = (e) => {
        const ingredientsArray = e.target.value.split(',').map(item => item.trim());
        setData('ingredients', ingredientsArray);
    };

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Crear Nueva Pizza</h2>}
        >
            <Head title="Crear Pizza" />

            <div className="py-12">
                <div className="max-w-2xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900">
                            <div className="flex justify-between items-center mb-6">
                                <h1 className="text-2xl font-bold text-gray-900">Crear Nueva Pizza</h1>
                                <Link
                                    href={safeRoute('pizzas.index')}
                                    className="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200"
                                >
                                    ← Volver
                                </Link>
                            </div>

                            <form onSubmit={handleSubmit} className="space-y-6">
                                {/* Nombre */}
                                <div>
                                    <label htmlFor="name" className="block text-sm font-medium text-gray-700">
                                        Nombre de la Pizza *
                                    </label>
                                    <input
                                        type="text"
                                        id="name"
                                        value={data.name}
                                        onChange={(e) => setData('name', e.target.value)}
                                        className="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500"
                                        placeholder="Ej: Margherita"
                                        required
                                    />
                                    {errors.name && (
                                        <p className="mt-2 text-sm text-red-600">{errors.name}</p>
                                    )}
                                </div>

                                {/* Categoría */}
                                <div>
                                    <label htmlFor="category_id" className="block text-sm font-medium text-gray-700">
                                        Categoría *
                                    </label>
                                    <select
                                        id="category_id"
                                        value={data.category_id}
                                        onChange={(e) => setData('category_id', e.target.value)}
                                        className="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500"
                                        required
                                    >
                                        <option value="">Seleccionar categoría</option>
                                        {categories.map(category => (
                                            <option key={category.id} value={category.id}>
                                                {category.name}
                                            </option>
                                        ))}
                                    </select>
                                    {errors.category_id && (
                                        <p className="mt-2 text-sm text-red-600">{errors.category_id}</p>
                                    )}
                                </div>

                                {/* Descripción */}
                                <div>
                                    <label htmlFor="description" className="block text-sm font-medium text-gray-700">
                                        Descripción
                                    </label>
                                    <textarea
                                        id="description"
                                        value={data.description}
                                        onChange={(e) => setData('description', e.target.value)}
                                        rows="3"
                                        className="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500"
                                        placeholder="Descripción de la pizza..."
                                    />
                                    {errors.description && (
                                        <p className="mt-2 text-sm text-red-600">{errors.description}</p>
                                    )}
                                </div>

                                {/* Precio */}
                                <div>
                                    <label htmlFor="price" className="block text-sm font-medium text-gray-700">
                                        Precio *
                                    </label>
                                    <input
                                        type="number"
                                        id="price"
                                        value={data.price}
                                        onChange={(e) => setData('price', e.target.value)}
                                        min="0"
                                        step="0.01"
                                        className="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500"
                                        placeholder="0.00"
                                        required
                                    />
                                    {errors.price && (
                                        <p className="mt-2 text-sm text-red-600">{errors.price}</p>
                                    )}
                                </div>

                                {/* Imagen */}
                                <div>
                                    <label htmlFor="image" className="block text-sm font-medium text-gray-700">
                                        URL de la Imagen
                                    </label>
                                    <input
                                        type="text"
                                        id="image"
                                        value={data.image}
                                        onChange={(e) => setData('image', e.target.value)}
                                        className="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500"
                                        placeholder="/assets/pizzas/pizza-1.webp"
                                    />
                                    {errors.image && (
                                        <p className="mt-2 text-sm text-red-600">{errors.image}</p>
                                    )}
                                </div>

                                {/* Ingredientes */}
                                <div>
                                    <label htmlFor="ingredients" className="block text-sm font-medium text-gray-700">
                                        Ingredientes
                                    </label>
                                    <input
                                        type="text"
                                        id="ingredients"
                                        value={data.ingredients.join(', ')}
                                        onChange={handleIngredientChange}
                                        className="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500"
                                        placeholder="Salsa, mozarella, oregano (separados por comas)"
                                    />
                                    <p className="mt-2 text-sm text-gray-500">
                                        Separa los ingredientes con comas
                                    </p>
                                    {errors.ingredients && (
                                        <p className="mt-2 text-sm text-red-600">{errors.ingredients}</p>
                                    )}
                                </div>

                                {/* Tiempo de preparación */}
                                <div>
                                    <label htmlFor="preparation_time" className="block text-sm font-medium text-gray-700">
                                        Tiempo de Preparación (minutos)
                                    </label>
                                    <input
                                        type="number"
                                        id="preparation_time"
                                        value={data.preparation_time}
                                        onChange={(e) => setData('preparation_time', e.target.value)}
                                        min="1"
                                        className="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500"
                                        placeholder="15"
                                    />
                                    {errors.preparation_time && (
                                        <p className="mt-2 text-sm text-red-600">{errors.preparation_time}</p>
                                    )}
                                </div>

                                {/* Checkboxes */}
                                <div className="flex space-x-6">
                                    <div className="flex items-center">
                                        <input
                                            id="available"
                                            name="available"
                                            type="checkbox"
                                            checked={data.available}
                                            onChange={(e) => setData('available', e.target.checked)}
                                            className="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded"
                                        />
                                        <label htmlFor="available" className="ml-2 block text-sm text-gray-900">
                                            Disponible
                                        </label>
                                    </div>

                                    <div className="flex items-center">
                                        <input
                                            id="featured"
                                            name="featured"
                                            type="checkbox"
                                            checked={data.featured}
                                            onChange={(e) => setData('featured', e.target.checked)}
                                            className="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded"
                                        />
                                        <label htmlFor="featured" className="ml-2 block text-sm text-gray-900">
                                            Destacada
                                        </label>
                                    </div>
                                </div>

                                {/* Botones */}
                                <div className="flex justify-end space-x-3">
                                    <Link
                                        href={safeRoute('pizzas.index')}
                                        className="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg transition duration-200"
                                    >
                                        Cancelar
                                    </Link>
                                    <button
                                        type="submit"
                                        disabled={processing}
                                        className="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200 disabled:opacity-50"
                                    >
                                        {processing ? 'Creando...' : 'Crear Pizza'}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
