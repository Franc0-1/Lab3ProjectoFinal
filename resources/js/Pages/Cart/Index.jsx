import { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import Layout from '@/Layouts/Layout';

export default function CartIndex({ cartItems, total, count, requiresAuth = false }) {
    const [isUpdating, setIsUpdating] = useState(false);
    
    // Mostrar mensaje de éxito si existe
    const urlParams = new URLSearchParams(window.location.search);
    const successMessage = urlParams.get('success');
    
    if (successMessage) {
        console.log('Success message:', successMessage);
        // Remover el parámetro de la URL para que no se muestre de nuevo
        window.history.replaceState({}, document.title, window.location.pathname);
    }
    
    // Función para testear desde la consola
    window.testCartFunctions = {
        updateQuantity: (pizzaId, newQuantity) => updateQuantity(pizzaId, newQuantity),
        clearCart: () => clearCart(),
        removeItem: (pizzaId) => removeItem(pizzaId),
        updateQuantityForm: (pizzaId, newQuantity) => updateQuantityForm(pizzaId, newQuantity),
        clearCartForm: () => clearCartForm(),
        removeItemForm: (pizzaId) => removeItemForm(pizzaId)
    };
    
    // Método alternativo usando formularios
    const updateQuantityForm = (pizzaId, newQuantity) => {
        console.log('updateQuantityForm called with:', { pizzaId, newQuantity });
        
        if (newQuantity < 1 || newQuantity > 10) {
            console.log('Cantidad inválida:', newQuantity);
            alert('Cantidad inválida. Debe ser entre 1 y 10.');
            return;
        }
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        console.log('CSRF Token found:', csrfToken);
        
        if (!csrfToken) {
            console.error('No se encontró el token CSRF');
            alert('Error: Token CSRF no encontrado. Recarga la página.');
            return;
        }
        
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/cart/update/${pizzaId}`;
        form.style.display = 'none';
        
        console.log('Creando formulario para:', form.action);
        
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'PATCH';
        
        const tokenInput = document.createElement('input');
        tokenInput.type = 'hidden';
        tokenInput.name = '_token';
        tokenInput.value = csrfToken;
        
        const quantityInput = document.createElement('input');
        quantityInput.type = 'hidden';
        quantityInput.name = 'quantity';
        quantityInput.value = newQuantity;
        
        form.appendChild(methodInput);
        form.appendChild(tokenInput);
        form.appendChild(quantityInput);
        
        document.body.appendChild(form);
        
        console.log('Enviando formulario...');
        form.submit();
    };
    
    const clearCartForm = () => {
        if (!confirm('¿Estás seguro de que quieres vaciar el carrito?')) return;
        
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/cart/clear';
        form.style.display = 'none';
        
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        
        const tokenInput = document.createElement('input');
        tokenInput.type = 'hidden';
        tokenInput.name = '_token';
        tokenInput.value = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        
        form.appendChild(methodInput);
        form.appendChild(tokenInput);
        
        document.body.appendChild(form);
        form.submit();
    };
    
    const removeItemForm = (pizzaId) => {
        if (!confirm('¿Estás seguro de que quieres eliminar este item del carrito?')) return;
        
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/cart/delete/${pizzaId}`;
        form.style.display = 'none';
        
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        
        const tokenInput = document.createElement('input');
        tokenInput.type = 'hidden';
        tokenInput.name = '_token';
        tokenInput.value = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        
        form.appendChild(methodInput);
        form.appendChild(tokenInput);
        
        document.body.appendChild(form);
        form.submit();
    };

    const updateQuantity = async (pizzaId, newQuantity) => {
        console.log('updateQuantity called with:', { pizzaId, newQuantity });
        
        if (newQuantity < 1 || newQuantity > 10) {
            console.log('Invalid quantity:', newQuantity);
            return;
        }
        
        setIsUpdating(true);
        
        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            console.log('CSRF Token:', csrfToken);
            
            if (!csrfToken) {
                alert('Token CSRF no encontrado. Recarga la página.');
                window.location.reload();
                return;
            }
            
            const response = await fetch(`/cart/update/${pizzaId}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ quantity: newQuantity })
            });
            
            console.log('Response status:', response.status);
            
            if (response.ok) {
                const result = await response.json();
                console.log('Update result:', result);
                // Forzar recarga inmediata
                setTimeout(() => {
                    router.reload();
                }, 100);
            } else {
                const errorText = await response.text();
                console.error('Error response:', errorText);
                alert('Error al actualizar la cantidad: ' + response.status + '\n' + errorText);
            }
        } catch (error) {
            console.error('Error updating quantity:', error);
            alert('Error al actualizar la cantidad: ' + error.message);
        } finally {
            setIsUpdating(false);
        }
    };

    const removeItem = async (pizzaId) => {
        if (!confirm('¿Estás seguro de que quieres eliminar este item del carrito?')) return;
        
        setIsUpdating(true);
        
        try {
            const response = await fetch(`/cart/delete/${pizzaId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                }
            });
            
            if (response.ok) {
                const result = await response.json();
                console.log(result.message);
                router.reload();
            } else {
                console.error('Error al eliminar el item');
                alert('Error al eliminar el item del carrito');
            }
        } catch (error) {
            console.error('Error removing item:', error);
            alert('Error al eliminar el item del carrito');
        } finally {
            setIsUpdating(false);
        }
    };

    const clearCart = async () => {
        console.log('clearCart called');
        
        if (!confirm('¿Estás seguro de que quieres vaciar el carrito?')) {
            console.log('User cancelled clear cart');
            return;
        }
        
        setIsUpdating(true);
        
        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            console.log('CSRF Token for clear:', csrfToken);
            
            const response = await fetch('/cart/clear', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                }
            });
            
            console.log('Clear response status:', response.status);
            
            if (response.ok) {
                const result = await response.json();
                console.log('Clear result:', result);
                // Redirigir al menú de pizzas después de vaciar el carrito
                // Desactivar Inertia antes de navegar
                document.addEventListener('inertia:before', (event) => {
                    event.preventDefault();
                }, { once: true });
                
                // Navegar inmediatamente
                window.location.replace('/?from-cart=true');
            } else {
                const errorText = await response.text();
                console.error('Error clearing cart:', errorText);
                alert('Error al vaciar el carrito: ' + response.status);
            }
        } catch (error) {
            console.error('Error clearing cart:', error);
            alert('Error al vaciar el carrito: ' + error.message);
        } finally {
            setIsUpdating(false);
        }
    };

    const formatPrice = (price) => {
        return new Intl.NumberFormat('es-CO', {
            style: 'currency',
            currency: 'COP',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(price);
    };

    const handleWhatsAppOrder = () => {
        let message = '¡Hola! Me gustaría hacer un pedido:\n\n';
        
        cartItems.forEach((item, index) => {
            message += `${index + 1}. ${item.pizza.nombre} (x${item.quantity})\n`;
            message += `   Ingredientes: ${item.pizza.ingredientes.join(', ')}\n`;
            message += `   Precio: $${item.pizza.precio.toLocaleString()}\n`;
            message += `   Subtotal: $${item.total.toLocaleString()}\n\n`;
        });
        
        message += `*Total del pedido: $${total.toLocaleString()}*\n\n`;
        message += '¿Podrían confirmar la disponibilidad y el tiempo de entrega?';
        
        const phoneNumber = '5493624123456'; // Número de teléfono de la pizzería
        const whatsappUrl = `https://wa.me/${phoneNumber}?text=${encodeURIComponent(message)}`;
        
        window.open(whatsappUrl, '_blank');
    };

    if (cartItems.length === 0) {
        return (
            <Layout>
                <Head title="Carrito de Compras" />
                <div className="min-h-screen bg-gradient-to-br from-orange-50 via-yellow-50 to-red-50 pt-20">
                    <div className="max-w-4xl mx-auto px-4 py-8">
                        <div className="text-center">
                            <div className="bg-white rounded-2xl shadow-lg p-8 mb-6">
                                <div className="text-6xl mb-4">🛒</div>
                                <h1 className="text-3xl font-bold text-gray-800 mb-4">Tu carrito está vacío</h1>
                                <p className="text-gray-600 mb-8">¡Agrega algunas deliciosas pizzas para empezar!</p>
                                <button
                                    onClick={() => {
                                        // Desactivar Inertia antes de navegar
                                        document.addEventListener('inertia:before', (event) => {
                                            event.preventDefault();
                                        }, { once: true });
                                        
                                        // Navegar inmediatamente
                                        window.location.replace('/?from-cart=true');
                                    }}
                                    className="bg-gradient-to-r from-orange-500 to-red-500 text-white px-8 py-3 rounded-lg hover:from-orange-600 hover:to-red-600 transition-all duration-300 font-semibold shadow-lg hover:shadow-xl inline-block cursor-pointer"
                                >
                                    Ver Menú
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </Layout>
        );
    }

    return (
        <Layout>
            <Head title="Carrito de Compras" />
            <div className="min-h-screen bg-gradient-to-br from-orange-50 via-yellow-50 to-red-50 pt-20">
                <div className="max-w-6xl mx-auto px-4 py-8">
                    {/* Header */}
                    <div className="text-center mb-8">
                        <h1 className="text-4xl font-bold text-gray-800 mb-2">Tu Carrito</h1>
                        <p className="text-gray-600">Revisa tu pedido antes de continuar</p>
                    </div>

                    <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        {/* Cart Items */}
                        <div className="lg:col-span-2">
                            <div className="bg-white rounded-2xl shadow-lg overflow-hidden">
                                <div className="p-6 border-b border-gray-200">
                                    <div className="flex justify-between items-center">
                                        <h2 className="text-xl font-semibold text-gray-800">
                                            Items en tu carrito ({count})
                                        </h2>
                                        <button
                                            onClick={() => {
                                                console.log('Vaciar carrito clicked');
                                                if (confirm('¿Estás seguro de que quieres vaciar el carrito?')) {
                                                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                                                    if (!csrfToken) {
                                                        alert('Token CSRF no encontrado. Por favor, recarga la página.');
                                                        return;
                                                    }
                                                    setIsUpdating(true);
                                                    fetch('/cart/clear', {
                                                        method: 'DELETE',
                                                        headers: {
                                                            'X-CSRF-TOKEN': csrfToken,
                                                        }
                                                    })
                                                    .then(response => {
                                                        console.log('Clear cart response:', response.status);
                                        if (response.ok) {
                                            // Redirigir al menú de pizzas después de vaciar el carrito
                                            // Desactivar Inertia antes de navegar
                                            document.addEventListener('inertia:before', (event) => {
                                                event.preventDefault();
                                            }, { once: true });
                                            
                                            // Navegar inmediatamente
                                            window.location.replace('/?from-cart=true');
                                                        } else {
                                                            alert('Error al vaciar el carrito');
                                                        }
                                                    })
                                                    .catch(error => {
                                                        console.error('Error:', error);
                                                        alert('Error al vaciar el carrito');
                                                    })
                                                    .finally(() => setIsUpdating(false));
                                                }
                                            }}
                                            disabled={isUpdating}
                                            className="text-red-500 hover:text-red-700 text-sm font-medium transition-colors duration-200 disabled:opacity-50"
                                        >
                                            {isUpdating ? 'Vaciando...' : 'Vaciar carrito'}
                                        </button>
                                    </div>
                                </div>
                                
                                <div className="divide-y divide-gray-200">
                                    {cartItems.map((item) => (
                                        <div key={item.pizza.id} className="p-6 hover:bg-gray-50 transition-colors duration-200">
                                            <div className="flex items-center space-x-4">
                                                <div className="flex-shrink-0">
                                                    <img
                                                        src={`/assets/${item.pizza.imagen}`}
                                                        alt={item.pizza.nombre}
                                                        className="w-16 h-16 object-cover rounded-lg"
                                                    />
                                                </div>
                                                
                                                <div className="flex-1">
                                                    <h3 className="font-semibold text-gray-800">{item.pizza.nombre}</h3>
                                                    <p className="text-sm text-gray-600 mt-1">{item.pizza.ingredientes.join(', ')}</p>
                                                    <p className="text-orange-600 font-bold mt-2">{formatPrice(item.pizza.precio)}</p>
                                                </div>
                                                
                                                <div className="flex items-center space-x-3">
                                                    <div className="flex items-center space-x-2">
                                                    <button
                                                        onClick={() => {
                                                            console.log('Decrease quantity clicked for pizza', item.pizza.id);
                                                            if (item.quantity > 1) {
                                                                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                                                                if (!csrfToken) {
                                                                    alert('Token CSRF no encontrado. Por favor, recarga la página.');
                                                                    return;
                                                                }
                                                                setIsUpdating(true);
                                                                fetch(`/cart/update/${item.pizza.id}`, {
                                                                    method: 'PATCH',
                                                                    headers: {
                                                                        'Content-Type': 'application/json',
                                                                        'X-CSRF-TOKEN': csrfToken,
                                                                    },
                                                                    body: JSON.stringify({ quantity: item.quantity - 1 })
                                                                })
                                                                .then(response => {
                                                                    console.log('Decrease response:', response.status);
                                                                    if (response.ok) {
                                                                        router.reload();
                                                                    } else {
                                                                        alert('Error al actualizar cantidad');
                                                                    }
                                                                })
                                                                .catch(error => {
                                                                    console.error('Error:', error);
                                                                    alert('Error al actualizar cantidad');
                                                                })
                                                                .finally(() => setIsUpdating(false));
                                                            }
                                                        }}
                                                        disabled={isUpdating || item.quantity <= 1}
                                                        className="w-8 h-8 rounded-full bg-gray-200 hover:bg-gray-300 flex items-center justify-center text-gray-600 hover:text-gray-800 transition-colors duration-200 disabled:opacity-50"
                                                    >
                                                        -
                                                    </button>
                                                        <span className="w-8 text-center font-semibold">{item.quantity}</span>
                                                        <button
                                                            onClick={() => {
                                                                console.log('Increase quantity clicked for pizza', item.pizza.id);
                                                                if (item.quantity < 10) {
                                                                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                                                                    if (!csrfToken) {
                                                                        alert('Token CSRF no encontrado. Por favor, recarga la página.');
                                                                        return;
                                                                    }
                                                                    setIsUpdating(true);
                                                                    fetch(`/cart/update/${item.pizza.id}`, {
                                                                        method: 'PATCH',
                                                                        headers: {
                                                                            'Content-Type': 'application/json',
                                                                            'X-CSRF-TOKEN': csrfToken,
                                                                        },
                                                                        body: JSON.stringify({ quantity: item.quantity + 1 })
                                                                    })
                                                                    .then(response => {
                                                                        console.log('Increase response:', response.status);
                                                                        if (response.ok) {
                                                                            router.reload();
                                                                        } else {
                                                                            alert('Error al actualizar cantidad');
                                                                        }
                                                                    })
                                                                    .catch(error => {
                                                                        console.error('Error:', error);
                                                                        alert('Error al actualizar cantidad');
                                                                    })
                                                                    .finally(() => setIsUpdating(false));
                                                                }
                                                            }}
                                                            disabled={isUpdating || item.quantity >= 10}
                                                            className="w-8 h-8 rounded-full bg-gray-200 hover:bg-gray-300 flex items-center justify-center text-gray-600 hover:text-gray-800 transition-colors duration-200 disabled:opacity-50"
                                                        >
                                                            +
                                                        </button>
                                                    </div>
                                                    
                                                    <div className="text-right">
                                                        <p className="font-bold text-gray-800">{formatPrice(item.total)}</p>
                                                    </div>
                                                    
                                                    <button
                                                        onClick={() => {
                                                            console.log('Delete item clicked for pizza', item.pizza.id);
                                                            if (confirm('\u00bfEst\u00e1s seguro de que quieres eliminar este item?')) {
                                                                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                                                                if (!csrfToken) {
                                                                    alert('Token CSRF no encontrado. Por favor, recarga la página.');
                                                                    return;
                                                                }
                                                                setIsUpdating(true);
                                                                fetch(`/cart/delete/${item.pizza.id}`, {
                                                                    method: 'DELETE',
                                                                    headers: {
                                                                        'X-CSRF-TOKEN': csrfToken,
                                                                    }
                                                                })
                                                                .then(response => {
                                                                    console.log('Delete response:', response.status);
                                                                    if (response.ok) {
                                                                        router.reload();
                                                                    } else {
                                                                        alert('Error al eliminar item');
                                                                    }
                                                                })
                                                                .catch(error => {
                                                                    console.error('Error:', error);
                                                                    alert('Error al eliminar item');
                                                                })
                                                                .finally(() => setIsUpdating(false));
                                                            }
                                                        }}
                                                        disabled={isUpdating}
                                                        className="text-red-500 hover:text-red-700 p-2 transition-colors duration-200 disabled:opacity-50"
                                                    >
                                                        🗑️
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            </div>
                        </div>

                        {/* Order Summary */}
                        <div className="lg:col-span-1">
                            <div className="bg-white rounded-2xl shadow-lg p-6 sticky top-24">
                                <h2 className="text-xl font-semibold text-gray-800 mb-4">Resumen del Pedido</h2>
                                
                                <div className="space-y-3 mb-6">
                                    <div className="flex justify-between text-gray-600">
                                        <span>Subtotal:</span>
                                        <span>{formatPrice(total)}</span>
                                    </div>
                                    <div className="flex justify-between text-gray-600">
                                        <span>Envío:</span>
                                        <span className="text-green-600">Gratis</span>
                                    </div>
                                    <div className="border-t pt-3">
                                        <div className="flex justify-between text-lg font-bold text-gray-800">
                                            <span>Total:</span>
                                            <span>{formatPrice(total)}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div className="space-y-3">
                                    <button
                                        onClick={handleWhatsAppOrder}
                                        className="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition-colors duration-300 font-semibold shadow-lg hover:shadow-xl flex items-center justify-center space-x-2"
                                    >
                                        <svg className="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.531 3.488"/>
                                        </svg>
                                        <span>Pedir por WhatsApp</span>
                                    </button>
                                    
                                    {requiresAuth ? (
                                        <Link
                                            href="/login"
                                            className="w-full bg-gradient-to-r from-orange-500 to-red-500 text-white py-3 rounded-lg hover:from-orange-600 hover:to-red-600 transition-all duration-300 font-semibold shadow-lg hover:shadow-xl text-center block"
                                        >
                                            Iniciar Sesión para Continuar
                                        </Link>
                                    ) : (
                                        <Link
                                            href="/orders/create"
                                            className="w-full bg-gradient-to-r from-orange-500 to-red-500 text-white py-3 rounded-lg hover:from-orange-600 hover:to-red-600 transition-all duration-300 font-semibold shadow-lg hover:shadow-xl text-center block"
                                        >
                                            Pedir por la App
                                        </Link>
                                    )}
                                </div>
                                
                                <button
                                    onClick={() => {
                                        // Desactivar Inertia antes de navegar
                                        document.addEventListener('inertia:before', (event) => {
                                            event.preventDefault();
                                        }, { once: true });
                                        
                                        // Navegar inmediatamente
                                        window.location.replace('/?from-cart=true');
                                    }}
                                    className="w-full bg-gray-100 text-gray-800 py-3 rounded-lg hover:bg-gray-200 transition-colors duration-300 font-semibold text-center block mt-3 cursor-pointer"
                                >
                                    Continuar Comprando
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </Layout>
    );
}
