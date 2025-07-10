import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, useForm } from '@inertiajs/react';

export default function Edit({ auth, customer }) {
    const { data, setData, put, processing, errors, reset } = useForm({
        first_name: customer.first_name || '',
        last_name: customer.last_name || '',
        email: customer.email || '',
        phone: customer.phone || '',
        address: customer.address || '',
        neighborhood: customer.neighborhood || '',
        latitude: customer.latitude || '',
        longitude: customer.longitude || '',
        is_frequent: customer.is_frequent || false,
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        put(route('customers.update', customer.id), {
            onSuccess: () => {
                // Don't reset form on success for edit page
            },
        });
    };

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={
                <div className="flex justify-between items-center">
                    <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                        Edit Customer: {customer.first_name} {customer.last_name}
                    </h2>
                    <div className="flex space-x-4">
                        <Link
                            href={route('customers.show', customer.id)}
                            className="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
                        >
                            View Customer
                        </Link>
                        <Link
                            href={route('customers.index')}
                            className="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
                        >
                            Back to Customers
                        </Link>
                    </div>
                </div>
            }
        >
            <Head title={`Edit Customer - ${customer.first_name} ${customer.last_name}`} />

            <div className="py-12">
                <div className="max-w-2xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900">
                            <form onSubmit={handleSubmit} className="space-y-6">
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    {/* First Name */}
                                    <div>
                                        <label htmlFor="first_name" className="block text-sm font-medium text-gray-700">
                                            First Name
                                        </label>
                                        <input
                                            type="text"
                                            id="first_name"
                                            value={data.first_name}
                                            onChange={(e) => setData('first_name', e.target.value)}
                                            className={`mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm ${
                                                errors.first_name ? 'border-red-500' : ''
                                            }`}
                                            required
                                        />
                                        {errors.first_name && (
                                            <p className="mt-2 text-sm text-red-600">{errors.first_name}</p>
                                        )}
                                    </div>

                                    {/* Last Name */}
                                    <div>
                                        <label htmlFor="last_name" className="block text-sm font-medium text-gray-700">
                                            Last Name
                                        </label>
                                        <input
                                            type="text"
                                            id="last_name"
                                            value={data.last_name}
                                            onChange={(e) => setData('last_name', e.target.value)}
                                            className={`mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm ${
                                                errors.last_name ? 'border-red-500' : ''
                                            }`}
                                            required
                                        />
                                        {errors.last_name && (
                                            <p className="mt-2 text-sm text-red-600">{errors.last_name}</p>
                                        )}
                                    </div>
                                </div>

                                {/* Email */}
                                <div>
                                    <label htmlFor="email" className="block text-sm font-medium text-gray-700">
                                        Email
                                    </label>
                                    <input
                                        type="email"
                                        id="email"
                                        value={data.email}
                                        onChange={(e) => setData('email', e.target.value)}
                                        className={`mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm ${
                                            errors.email ? 'border-red-500' : ''
                                        }`}
                                    />
                                    {errors.email && (
                                        <p className="mt-2 text-sm text-red-600">{errors.email}</p>
                                    )}
                                </div>

                                {/* Phone */}
                                <div>
                                    <label htmlFor="phone" className="block text-sm font-medium text-gray-700">
                                        Phone
                                    </label>
                                    <input
                                        type="tel"
                                        id="phone"
                                        value={data.phone}
                                        onChange={(e) => setData('phone', e.target.value)}
                                        className={`mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm ${
                                            errors.phone ? 'border-red-500' : ''
                                        }`}
                                        required
                                    />
                                    {errors.phone && (
                                        <p className="mt-2 text-sm text-red-600">{errors.phone}</p>
                                    )}
                                </div>

                                {/* Address */}
                                <div>
                                    <label htmlFor="address" className="block text-sm font-medium text-gray-700">
                                        Address
                                    </label>
                                    <textarea
                                        id="address"
                                        rows={3}
                                        value={data.address}
                                        onChange={(e) => setData('address', e.target.value)}
                                        className={`mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm ${
                                            errors.address ? 'border-red-500' : ''
                                        }`}
                                        required
                                    />
                                    {errors.address && (
                                        <p className="mt-2 text-sm text-red-600">{errors.address}</p>
                                    )}
                                </div>

                                {/* Neighborhood */}
                                <div>
                                    <label htmlFor="neighborhood" className="block text-sm font-medium text-gray-700">
                                        Neighborhood
                                    </label>
                                    <input
                                        type="text"
                                        id="neighborhood"
                                        value={data.neighborhood}
                                        onChange={(e) => setData('neighborhood', e.target.value)}
                                        className={`mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm ${
                                            errors.neighborhood ? 'border-red-500' : ''
                                        }`}
                                    />
                                    {errors.neighborhood && (
                                        <p className="mt-2 text-sm text-red-600">{errors.neighborhood}</p>
                                    )}
                                </div>

                                {/* Location Coordinates */}
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    {/* Latitude */}
                                    <div>
                                        <label htmlFor="latitude" className="block text-sm font-medium text-gray-700">
                                            Latitude
                                        </label>
                                        <input
                                            type="number"
                                            id="latitude"
                                            step="any"
                                            value={data.latitude}
                                            onChange={(e) => setData('latitude', e.target.value)}
                                            className={`mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm ${
                                                errors.latitude ? 'border-red-500' : ''
                                            }`}
                                            placeholder="e.g., 40.7128"
                                        />
                                        {errors.latitude && (
                                            <p className="mt-2 text-sm text-red-600">{errors.latitude}</p>
                                        )}
                                    </div>

                                    {/* Longitude */}
                                    <div>
                                        <label htmlFor="longitude" className="block text-sm font-medium text-gray-700">
                                            Longitude
                                        </label>
                                        <input
                                            type="number"
                                            id="longitude"
                                            step="any"
                                            value={data.longitude}
                                            onChange={(e) => setData('longitude', e.target.value)}
                                            className={`mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm ${
                                                errors.longitude ? 'border-red-500' : ''
                                            }`}
                                            placeholder="e.g., -74.0060"
                                        />
                                        {errors.longitude && (
                                            <p className="mt-2 text-sm text-red-600">{errors.longitude}</p>
                                        )}
                                    </div>
                                </div>

                                {/* Frequent Customer */}
                                <div>
                                    <label className="flex items-center">
                                        <input
                                            type="checkbox"
                                            checked={data.is_frequent}
                                            onChange={(e) => setData('is_frequent', e.target.checked)}
                                            className="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        />
                                        <span className="ml-2 text-sm text-gray-600">
                                            Mark as frequent customer
                                        </span>
                                    </label>
                                </div>

                                {/* Submit Button */}
                                <div className="flex justify-end space-x-4">
                                    <Link
                                        href={route('customers.show', customer.id)}
                                        className="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
                                    >
                                        Cancel
                                    </Link>
                                    <button
                                        type="submit"
                                        disabled={processing}
                                        className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded disabled:opacity-50"
                                    >
                                        {processing ? 'Updating...' : 'Update Customer'}
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
