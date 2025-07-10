import { Head, Link } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';

export default function ReportsIndex({ auth, stats }) {
    const handleExport = (type, format) => {
        const url = `/reports/${type}/${format}`;
        window.open(url, '_blank');
    };

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Reportes</h2>}
        >
            <Head title="Reportes" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900">
                            <div className="flex justify-between items-center mb-6">
                                <h1 className="text-2xl font-bold text-gray-900">Reportes del Sistema</h1>
                            </div>

                            {/* Estadísticas */}
                            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                                <div className="bg-blue-50 p-6 rounded-lg">
                                    <h3 className="text-lg font-semibold text-blue-700 mb-2">Total Pizzas</h3>
                                    <p className="text-3xl font-bold text-blue-600">{stats.total_pizzas}</p>
                                    <p className="text-sm text-blue-500">Pizzas disponibles: {stats.active_pizzas}</p>
                                </div>

                                <div className="bg-green-50 p-6 rounded-lg">
                                    <h3 className="text-lg font-semibold text-green-700 mb-2">Total Clientes</h3>
                                    <p className="text-3xl font-bold text-green-600">{stats.total_customers}</p>
                                    <p className="text-sm text-green-500">Clientes frecuentes: {stats.frequent_customers}</p>
                                </div>

                                <div className="bg-yellow-50 p-6 rounded-lg">
                                    <h3 className="text-lg font-semibold text-yellow-700 mb-2">Categorías</h3>
                                    <p className="text-3xl font-bold text-yellow-600">{stats.total_categories}</p>
                                    <p className="text-sm text-yellow-500">Categorías activas: {stats.active_categories}</p>
                                </div>

                                <div className="bg-purple-50 p-6 rounded-lg">
                                    <h3 className="text-lg font-semibold text-purple-700 mb-2">Total Órdenes</h3>
                                    <p className="text-3xl font-bold text-purple-600">{stats.total_orders}</p>
                                    <p className="text-sm text-purple-500">Pedidos registrados</p>
                                </div>
                            </div>

                            {/* Exportaciones */}
                            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                {/* Reportes de Pizzas */}
                                <div className="bg-white border border-gray-200 rounded-lg p-6">
                                    <h3 className="text-lg font-semibold text-gray-900 mb-4">Reporte de Pizzas</h3>
                                    <p className="text-gray-600 mb-4">Exportar información de todas las pizzas disponibles</p>
                                    <div className="space-y-2">
                                        <button
                                            onClick={() => handleExport('pizzas', 'excel')}
                                            className="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition duration-200"
                                        >
                                            Exportar Excel
                                        </button>
                                        <button
                                            onClick={() => handleExport('pizzas', 'pdf')}
                                            className="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition duration-200"
                                        >
                                            Exportar PDF
                                        </button>
                                    </div>
                                </div>

                                {/* Reportes de Clientes */}
                                <div className="bg-white border border-gray-200 rounded-lg p-6">
                                    <h3 className="text-lg font-semibold text-gray-900 mb-4">Reporte de Clientes</h3>
                                    <p className="text-gray-600 mb-4">Exportar información de todos los clientes registrados</p>
                                    <div className="space-y-2">
                                        <button
                                            onClick={() => handleExport('customers', 'excel')}
                                            className="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition duration-200"
                                        >
                                            Exportar Excel
                                        </button>
                                        <button
                                            onClick={() => handleExport('customers', 'pdf')}
                                            className="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition duration-200"
                                        >
                                            Exportar PDF
                                        </button>
                                    </div>
                                </div>

                                {/* Reportes de Categorías */}
                                <div className="bg-white border border-gray-200 rounded-lg p-6">
                                    <h3 className="text-lg font-semibold text-gray-900 mb-4">Reporte de Categorías</h3>
                                    <p className="text-gray-600 mb-4">Exportar información de todas las categorías</p>
                                    <div className="space-y-2">
                                        <button
                                            onClick={() => handleExport('categories', 'excel')}
                                            className="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition duration-200"
                                        >
                                            Exportar Excel
                                        </button>
                                        <button
                                            onClick={() => handleExport('categories', 'pdf')}
                                            className="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition duration-200"
                                        >
                                            Exportar PDF
                                        </button>
                                    </div>
                                </div>

                                {/* Reportes de Órdenes */}
                                <div className="bg-white border border-gray-200 rounded-lg p-6">
                                    <h3 className="text-lg font-semibold text-gray-900 mb-4">Reporte de Órdenes</h3>
                                    <p className="text-gray-600 mb-4">Exportar información de todas las órdenes</p>
                                    <div className="space-y-2">
                                        <button
                                            onClick={() => handleExport('orders', 'excel')}
                                            className="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition duration-200"
                                        >
                                            Exportar Excel
                                        </button>
                                        <button
                                            onClick={() => handleExport('orders', 'pdf')}
                                            className="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition duration-200"
                                        >
                                            Exportar PDF
                                        </button>
                                    </div>
                                </div>

                                {/* Reporte General */}
                                <div className="bg-white border border-gray-200 rounded-lg p-6 md:col-span-2">
                                    <h3 className="text-lg font-semibold text-gray-900 mb-4">Reporte General</h3>
                                    <p className="text-gray-600 mb-4">Exportar reporte completo del sistema</p>
                                    <div className="space-y-2">
                                        <button
                                            onClick={() => handleExport('general', 'pdf')}
                                            className="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded transition duration-200"
                                        >
                                            Exportar Reporte General (PDF)
                                        </button>
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
