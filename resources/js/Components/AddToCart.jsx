import { useState } from 'react';
import { router } from '@inertiajs/react';

export default function AddToCart({ pizza, className = '' }) {
    const [quantity, setQuantity] = useState(1);
    const [isAdding, setIsAdding] = useState(false);

    const handleAddToCart = async () => {
        setIsAdding(true);
        
        try {
            router.post('/cart/add', {
                pizza_id: pizza.id,
                quantity: quantity
            }, {
                preserveScroll: true,
                preserveState: true,
                onSuccess: () => {
                    // Disparar evento personalizado para actualizar el contador
                    window.dispatchEvent(new CustomEvent('cartUpdated'));
                    setQuantity(1);
                },
                onError: (errors) => {
                    console.error('Error adding to cart:', errors);
                },
                onFinish: () => {
                    setIsAdding(false);
                }
            });
        } catch (error) {
            console.error('Error adding to cart:', error);
            setIsAdding(false);
        }
    };

    return (
        <div className={`flex items-center space-x-2 ${className}`}>
            <div className="flex items-center border border-gray-300 rounded-md">
                <button
                    type="button"
                    onClick={() => setQuantity(Math.max(1, quantity - 1))}
                    className="px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-l-md transition-colors duration-200"
                    disabled={quantity <= 1}
                >
                    -
                </button>
                <span className="px-4 py-1 bg-white text-gray-700 font-medium">
                    {quantity}
                </span>
                <button
                    type="button"
                    onClick={() => setQuantity(quantity + 1)}
                    className="px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-r-md transition-colors duration-200"
                >
                    +
                </button>
            </div>
            
            <button
                type="button"
                onClick={handleAddToCart}
                disabled={isAdding}
                className={`px-4 py-2 bg-orange-500 text-white rounded-md hover:bg-orange-600 transition-colors duration-200 font-medium ${
                    isAdding ? 'opacity-50 cursor-not-allowed' : ''
                }`}
            >
                {isAdding ? 'Agregando...' : 'Agregar al carrito'}
            </button>
        </div>
    );
}
