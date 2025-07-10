import { useState, useEffect } from 'react';
import { Link } from '@inertiajs/react';

export default function CartCounter({ initialCount = 0 }) {
    const [count, setCount] = useState(initialCount);

    const fetchCartCount = async () => {
        try {
            const response = await fetch('/cart/count');
            if (response.ok) {
                const data = await response.json();
                setCount(data.count);
            }
        } catch (error) {
            console.error('Error fetching cart count:', error);
        }
    };

    useEffect(() => {
        fetchCartCount();
        
        // Actualizar contador cuando se agregue un item al carrito
        const handleCartUpdate = () => {
            fetchCartCount();
        };

        window.addEventListener('cartUpdated', handleCartUpdate);
        
        return () => {
            window.removeEventListener('cartUpdated', handleCartUpdate);
        };
    }, []);

    return (
        <Link
            href="/cart"
            className="relative inline-flex items-center px-4 py-2 text-white hover:text-orange-200 transition-colors duration-200"
        >
            <svg
                className="w-6 h-6"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg"
            >
                <path
                    strokeLinecap="round"
                    strokeLinejoin="round"
                    strokeWidth={2}
                    d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M17 17a2 2 0 11-4 0 2 2 0 014 0zM9 17a2 2 0 11-4 0 2 2 0 014 0z"
                />
            </svg>
            {count > 0 && (
                <span className="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold">
                    {count > 99 ? '99+' : count}
                </span>
            )}
        </Link>
    );
}
