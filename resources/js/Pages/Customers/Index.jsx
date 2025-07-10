import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, router } from '@inertiajs/react';
import { useState } from 'react';
import axios from 'axios';

export default function Index({ auth, customers, filters }) {
    const [searchTerm, setSearchTerm] = useState(filters.search || '');
    const [selectedCustomers, setSelectedCustomers] = useState([]);
    const [loading, setLoading] = useState(false);

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

    const handleSearch = (e) => {
        e.preventDefault();
        router.get(safeRoute('customers.index'), { search: searchTerm }, { 
            preserveState: true, 
            preserveScroll: true 
        });
    };

    const handleToggleFrequent = async (customerId) => {
        try {
            setLoading(true);
            await axios.post(safeRoute('customers.toggle-frequent', customerId));
            router.reload({ only: ['customers'] });
        } catch (error) {
            console.error('Error toggling frequent status:', error);
        } finally {
            setLoading(false);
        }
    };

    const handleSelectAll = (e) => {
        if (e.target.checked) {
            setSelectedCustomers(customers.data.map(customer => customer.id));
        } else {
            setSelectedCustomers([]);
        }
    };

    const handleSelectCustomer = (customerId) => {
        setSelectedCustomers(prev => 
            prev.includes(customerId) 
                ? prev.filter(id => id !== customerId)
                : [...prev, customerId]
        );
    };

    const handleBulkAction = async (action) => {
        if (selectedCustomers.length === 0) {
            alert('Please select customers first');
            return;
        }

        try {
            setLoading(true);
            await axios.post(safeRoute('customers.bulk-action'), {
                action,
                customer_ids: selectedCustomers
            });
            setSelectedCustomers([]);
            router.reload({ only: ['customers'] });
        } catch (error) {
            console.error('Error performing bulk action:', error);
        } finally {
            setLoading(false);
        }
    };

    const handleDelete = async (customerId) => {
        if (confirm('Are you sure you want to delete this customer?')) {
            try {
                setLoading(true);
                await axios.delete(safeRoute('customers.destroy', customerId));
                router.reload({ only: ['customers'] });
            } catch (error) {
                console.error('Error deleting customer:', error);
            } finally {
                setLoading(false);
            }
        }
    };

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={
                <div className="flex justify-between items-center">
                    <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                        Customers
                    </h2>
                    <Link
                        href={safeRoute('customers.create')}
                        className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                    >
                        Add New Customer
                    </Link>
                </div>
            }
        >
            <Head title="Customers" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900">
                            {/* Search and Filters */}
                            <div className="mb-6 flex flex-col sm:flex-row gap-4">
                                <form onSubmit={handleSearch} className="flex-1">
                                    <div className="flex">
                                        <input
                                            type="text"
                                            placeholder="Search customers..."
                                            value={searchTerm}
                                            onChange={(e) => setSearchTerm(e.target.value)}
                                            className="flex-1 border border-gray-300 rounded-l-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        />
                                        <button
                                            type="submit"
                                            className="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded-r-md"
                                        >
                                            Search
                                        </button>
                                    </div>
                                </form>

                                {/* Bulk Actions */}
                                {selectedCustomers.length > 0 && (
                                    <div className="flex gap-2">
                                        <button
                                            onClick={() => handleBulkAction('mark_frequent')}
                                            disabled={loading}
                                            className="bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded disabled:opacity-50"
                                        >
                                            Mark as Frequent
                                        </button>
                                        <button
                                            onClick={() => handleBulkAction('unmark_frequent')}
                                            disabled={loading}
                                            className="bg-yellow-500 hover:bg-yellow-700 text-white px-4 py-2 rounded disabled:opacity-50"
                                        >
                                            Mark as Not Frequent
                                        </button>
                                    </div>
                                )}
                            </div>

                            {/* Customers Table */}
                            <div className="overflow-x-auto">
                                <table className="min-w-full bg-white border border-gray-200">
                                    <thead className="bg-gray-50">
                                        <tr>
                                            <th className="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                <input
                                                    type="checkbox"
                                                    onChange={handleSelectAll}
                                                    checked={selectedCustomers.length === customers.data.length && customers.data.length > 0}
                                                />
                                            </th>
                                            <th className="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Name
                                            </th>
                                            <th className="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Email
                                            </th>
                                            <th className="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Phone
                                            </th>
                                            <th className="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status
                                            </th>
                                            <th className="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Orders
                                            </th>
                                            <th className="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody className="bg-white divide-y divide-gray-200">
                                        {customers.data.map((customer) => (
                                            <tr key={customer.id} className="hover:bg-gray-50">
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    <input
                                                        type="checkbox"
                                                        checked={selectedCustomers.includes(customer.id)}
                                                        onChange={() => handleSelectCustomer(customer.id)}
                                                    />
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    <div className="text-sm font-medium text-gray-900">
                                                        {customer.name}
                                                    </div>
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    <div className="text-sm text-gray-900">{customer.email}</div>
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    <div className="text-sm text-gray-900">{customer.phone}</div>
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    <span className={`inline-flex px-2 py-1 text-xs font-semibold rounded-full ${
                                                        customer.frequent_customer 
                                                            ? 'bg-green-100 text-green-800' 
                                                            : 'bg-gray-100 text-gray-800'
                                                    }`}>
                                                        {customer.frequent_customer ? 'Frequent' : 'Regular'}
                                                    </span>
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {customer.orders_count || 0}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <div className="flex space-x-2">
                                                        <Link
                                                            href={safeRoute('customers.show', customer.id)}
                                                            className="text-blue-600 hover:text-blue-900"
                                                        >
                                                            View
                                                        </Link>
                                                        <Link
                                                            href={safeRoute('customers.edit', customer.id)}
                                                            className="text-indigo-600 hover:text-indigo-900"
                                                        >
                                                            Edit
                                                        </Link>
                                                        <button
                                                            onClick={() => handleToggleFrequent(customer.id)}
                                                            disabled={loading}
                                                            className="text-green-600 hover:text-green-900 disabled:opacity-50"
                                                        >
                                                            {customer.frequent_customer ? 'Unmark' : 'Mark'} Frequent
                                                        </button>
                                                        <button
                                                            onClick={() => handleDelete(customer.id)}
                                                            disabled={loading}
                                                            className="text-red-600 hover:text-red-900 disabled:opacity-50"
                                                        >
                                                            Delete
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        ))}
                                    </tbody>
                                </table>
                            </div>

                            {/* Pagination */}
                            {customers.links && (
                                <div className="mt-6 flex justify-center">
                                    <nav className="flex space-x-2">
                                        {customers.links.map((link, index) => {
                                            // Skip links with null or invalid URLs
                                            if (!link.url) {
                                                return (
                                                    <span
                                                        key={index}
                                                        className="px-3 py-2 rounded-md text-sm font-medium bg-gray-200 text-gray-400 border border-gray-300 cursor-not-allowed"
                                                        dangerouslySetInnerHTML={{ __html: link.label }}
                                                    />
                                                );
                                            }
                                            
                                            return (
                                                <Link
                                                    key={index}
                                                    href={link.url}
                                                    className={`px-3 py-2 rounded-md text-sm font-medium ${
                                                        link.active
                                                            ? 'bg-blue-500 text-white'
                                                            : 'bg-white text-gray-700 hover:bg-gray-50'
                                                    } border border-gray-300`}
                                                    dangerouslySetInnerHTML={{ __html: link.label }}
                                                />
                                            );
                                        })}
                                    </nav>
                                </div>
                            )}

                            {customers.data.length === 0 && (
                                <div className="text-center py-8">
                                    <p className="text-gray-500">No customers found.</p>
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
