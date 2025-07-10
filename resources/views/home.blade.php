@extends('layouts.main')

@section('content')
<!-- Navbar mejorada -->
<nav class="fixed top-0 left-0 right-0 bg-white/95 backdrop-blur-md z-50 shadow-sm border-b border-gray-100">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center">
                <img src="/assets/logo.svg" alt="LaQueva" class="h-8 w-auto">
                <span class="ml-2 text-lg font-bold text-gray-800 hidden sm:block">LaQueva</span>
            </div>
            <div class="flex items-center space-x-2 sm:space-x-4">
                <a href="#pizza-slider" class="text-gray-600 hover:text-red-600 transition-colors text-sm sm:text-base">
                    Menú
                </a>
                <a href="#contacto" class="text-gray-600 hover:text-red-600 transition-colors text-sm sm:text-base">
                    Contacto
                </a>
                
                @guest
                    <a href="/simple-login" class="text-gray-600 hover:text-red-600 transition-colors text-sm sm:text-base">
                        Iniciar Sesión
                    </a>
                    <a href="/simple-register" class="bg-red-600 text-white px-3 py-2 sm:px-4 sm:py-2 rounded-lg hover:bg-red-700 transition-colors text-sm sm:text-base">
                        Registrarse
                    </a>
                @else
                    <div class="relative">
                        <button class="flex items-center space-x-2 bg-white text-gray-800 px-3 py-2 sm:px-4 sm:py-2 rounded-lg hover:bg-gray-100 transition-colors text-sm sm:text-base border border-gray-300" onclick="toggleUserDropdown()">
                            <span class="bg-red-600 rounded-full h-6 w-6 sm:h-8 sm:w-8 flex items-center justify-center text-white text-xs sm:text-sm font-bold">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </span>
                            <span class="hidden sm:inline">{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div id="user-dropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50 border border-gray-200">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Mi Perfil
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="block">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    Cerrar Sesión
                                </button>
                            </form>
                        </div>
                    </div>
                @endguest
            </div>
        </div>
    </div>
</nav>

<div class="flex flex-col font-sora">
    <!-- Primera sección - Logo -->
    <section class="min-h-screen flex flex-col justify-start md:justify-center pt-24 items-center">
        <div id="logo-container" class="mb-8">
            <img src="/assets/logo.svg" alt="La Que Va Logo" style="width: 200px; height: auto;" class="logo-animated">
        </div>
        
        <div class="text-center">
            <h1 class="text-xl sm:text-4xl font-sora text-gray-800 mb-4">
                Bienvenido a<br>La Que Va
            </h1>
            <p class="text-gray-600 mb-8">
                Las mejores pizzas de Resistencia, Chaco
            </p>
        </div>
        
        <!-- Estado Component -->
        <div id="estado-component" class="mb-8">
            <div class="p-4 bg-green-600 rounded-lg font-bold text-xl text-white">
                🍕 Estamos tomando pedidos
            </div>
        </div>
        
        <!-- Bounce Arrow -->
        <div class="animate-bounce flex items-center justify-center mt-10">
            <svg class="w-8 h-8 mx-auto text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
            </svg>
        </div>
    </section>
    
    <!-- Segunda sección - Pizzas -->
    <section class="py-16 bg-yellow-50">
        <div class="container mx-auto px-4">
            <div id="pizza-slider">
                <!-- Título de la sección -->
                <div class="text-center w-full mb-8">
                    <h1 class="text-5xl sm:text-6xl text-red-600 font-sora mb-4">
                        Nuestras Pizzas
                    </h1>
                    <p class="text-gray-600 text-lg">
                        Selecciona tu pizza favorita
                    </p>
                </div>

                <!-- Contenedor de pizzas -->
                <div class="w-full py-4">
                    <div class="w-full max-w-4xl mx-auto">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6 pb-20" id="pizzas-grid">
                            @php
                                $pizzas = [
                                    ['id' => 1, 'nombre' => 'MUZZARELLA', 'ingredientes' => ['Salsa', 'muzza', 'oregano'], 'precio' => 5500, 'imagen' => 'pizzas/pizza-1.webp'],
                                    ['id' => 2, 'nombre' => 'MUZZA CON JAMON', 'ingredientes' => ['Salsa', 'muzza', 'jamon', 'oregano'], 'precio' => 6000, 'imagen' => 'pizzas/pizza-2.webp'],
                                    ['id' => 3, 'nombre' => 'FUGAZZETA', 'ingredientes' => ['Salsa', 'muzza', 'cebolla', 'oregano'], 'precio' => 6500, 'imagen' => 'pizzas/pizza-3.webp'],
                                    ['id' => 4, 'nombre' => 'NAPOLITANA', 'ingredientes' => ['Salsa', 'muzza', 'tomates', 'oregano', 'aceite de ajo'], 'precio' => 6500, 'imagen' => 'pizzas/pizza-4.webp'],
                                    ['id' => 5, 'nombre' => 'NAPO CON JAMON', 'ingredientes' => ['Salsa', 'muzza', 'jamon', 'tomates', 'oregano'], 'precio' => 7000, 'imagen' => 'pizzas/pizza-5.webp'],
                                    ['id' => 6, 'nombre' => 'FUGA CON JAMON', 'ingredientes' => ['Salsa', 'muzza', 'jamon', 'cebolla', 'oregano'], 'precio' => 7000, 'imagen' => 'pizzas/pizza-6.webp'],
                                    ['id' => 7, 'nombre' => 'ESPECIAL', 'ingredientes' => ['Salsa', 'muzza', 'jamon', 'tomates', 'cebolla', 'oregano'], 'precio' => 7000, 'imagen' => 'pizzas/pizza-7.webp'],
                                    ['id' => 8, 'nombre' => '2 MUZZA', 'ingredientes' => ['2 Pizzas Muzzarella'], 'precio' => 10000, 'imagen' => 'pizzas/pizza-8.webp', 'esPromo' => true, 'cantidadPizzas' => 2],
                                    ['id' => 9, 'nombre' => '1 MUZZA + 1 ESPECIAL', 'ingredientes' => ['1 Pizza Muzzarella', '1 Pizza Especial'], 'precio' => 12000, 'imagen' => 'pizzas/pizza-9.webp', 'esPromo' => true, 'cantidadPizzas' => 2],
                                    ['id' => 10, 'nombre' => '1 NAPO + 1 FUGA', 'ingredientes' => ['1 Pizza Napolitana', '1 Pizza Fugazzeta'], 'precio' => 12000, 'imagen' => 'pizzas/pizza-10.webp', 'esPromo' => true, 'cantidadPizzas' => 2],
                                    ['id' => 11, 'nombre' => '1 NAPO + 1 FUGA (CON JAMÓN)', 'ingredientes' => ['1 Pizza Napolitana con Jamón', '1 Pizza Fugazzeta con Jamón'], 'precio' => 13000, 'imagen' => 'pizzas/pizza-11.webp', 'esPromo' => true, 'cantidadPizzas' => 2],
                                ];
                            @endphp
                            
                            @foreach ($pizzas as $index => $pizza)
                                <div class="pizza-card" data-pizza-id="{{ $pizza['id'] }}" data-index="{{ $index }}">
                                    <div class="w-full rounded-xl overflow-hidden shadow-lg flex flex-col bg-red-500">
                                        <div class="flex w-full flex-1 p-2">
                                            <div class="w-1/4 items-center justify-center flex rounded-lg">
                                                <img src="/assets/{{ $pizza['imagen'] }}" 
                                                     alt="Pizza {{ $pizza['nombre'] }}" 
                                                     class="w-16 h-16 sm:w-28 sm:h-28 rounded-lg object-cover">
                                            </div>
                                            <div class="w-3/4 pl-3 flex flex-col justify-between">
                                                <div>
                                                    <div class="font-rubik-wet text-xl text-yellow-300">
                                                        {{ strtoupper($pizza['nombre']) }}
                                                    </div>
                                                    <p class="text-xs sm:text-sm text-white mb-1">
                                                        <span class="font-bold">Ingredientes: </span>
                                                        {{ strtolower(implode(', ', $pizza['ingredientes'])) }}.
                                                    </p>
                                                </div>

                                                <div class="flex justify-between items-center mt-2 sm:mt-3">
                                                    <div class="text-lg sm:text-xl font-bold text-white">
                                                        ${{ number_format($pizza['precio']) }}
                                                    </div>

                                                    <div class="flex items-center bg-white rounded-lg overflow-hidden">
                                                        <button class="decrease-btn px-1 sm:px-2 w-6 sm:w-8 py-1 text-gray-800 hover:text-white text-sm sm:text-xl font-bold cursor-pointer hover:bg-red-700 rounded-l-lg transition-colors"
                                                                data-pizza-id="{{ $pizza['id'] }}">
                                                            -
                                                        </button>
                                                        <span class="quantity-display px-1 sm:px-2 py-1 font-bold text-black text-sm sm:text-base min-w-[20px] text-center">
                                                            0
                                                        </span>
                                                        <button class="increase-btn px-1 sm:px-2 w-6 sm:w-8 py-1 text-gray-800 text-sm sm:text-xl font-bold cursor-pointer hover:text-white hover:bg-red-700 rounded-r-lg transition-colors"
                                                                data-pizza-id="{{ $pizza['id'] }}">
                                                            +
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sección de Contacto -->
    <section id="contacto" class="py-16 bg-gradient-to-br from-red-800 to-gray-900">
        <div class="container mx-auto px-4 text-gray-100">
            <div class="text-center mb-12">
                <h2 class="text-4xl sm:text-5xl font-bold text-yellow-400 mb-4">Contáctanos</h2>
                <p class="text-xl text-yellow-200 max-w-2xl mx-auto">
                    ¿Tienes alguna pregunta o quieres hacer un pedido? ¡Estamos aquí para ayudarte!
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-4xl mx-auto">
                <!-- WhatsApp -->
                <div class="bg-green-600 hover:bg-green-700 text-white p-6 rounded-2xl transition duration-300 transform hover:scale-105">
                    <div class="text-center">
                        <div class="mb-4">
                            <svg class="w-16 h-16 mx-auto" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.531 3.488"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2">WhatsApp</h3>
                        <p class="text-green-100 mb-4">Chatea con nosotros directamente</p>
                        <a href="https://wa.me/5493624123456?text=Hola%2C%20me%20interesa%20hacer%20un%20pedido%20de%20pizza" 
                           target="_blank" 
                           class="inline-block bg-white text-green-600 px-6 py-3 rounded-full font-bold hover:bg-green-50 transition duration-300">
                            Enviar mensaje
                        </a>
                    </div>
                </div>

                <!-- Email -->
                <div class="bg-blue-600 hover:bg-blue-700 text-white p-6 rounded-2xl transition duration-300 transform hover:scale-105">
                    <div class="text-center">
                        <div class="mb-4">
                            <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2">Email</h3>
                        <p class="text-blue-100 mb-4">Escríbenos por correo electrónico</p>
                        <a href="mailto:info@laquevapizza.com?subject=Consulta%20sobre%20pedido&body=Hola%2C%20me%20gustaría%20hacer%20una%20consulta..."
                           class="inline-block bg-white text-blue-600 px-6 py-3 rounded-full font-bold hover:bg-blue-50 transition duration-300">
                            Enviar email
                        </a>
                    </div>
                </div>

                <!-- Teléfono -->
                <div class="bg-red-600 hover:bg-red-700 text-white p-6 rounded-2xl transition duration-300 transform hover:scale-105">
                    <div class="text-center">
                        <div class="mb-4">
                            <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2">Teléfono</h3>
                        <p class="text-red-100 mb-4">Llámanos directamente</p>
                        <a href="tel:+5493624123456" 
                           class="inline-block bg-white text-red-600 px-6 py-3 rounded-full font-bold hover:bg-red-50 transition duration-300">
                            (362) 412-3456
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Horarios de atención -->
            <div class="mt-12 text-center">
                <div class="bg-gray-700 rounded-2xl p-6 max-w-2xl mx-auto">
                    <h3 class="text-2xl font-bold text-white mb-4">Horarios de Atención</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-gray-300">
                        <div>
                            <p class="font-medium">Lunes - Jueves</p>
                            <p class="text-yellow-400">18:00 - 00:00</p>
                        </div>
                        <div>
                            <p class="font-medium">Viernes - Sábado</p>
                            <p class="text-yellow-400">18:00 - 01:00</p>
                        </div>
                        <div>
                            <p class="font-medium">Domingo</p>
                            <p class="text-yellow-400">18:00 - 23:00</p>
                        </div>
                        <div>
                            <p class="font-medium">Delivery</p>
                            <p class="text-green-400">Disponible todos los días</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tercera sección - Footer -->
    <section class="sm:py-16">
        <div class="flex flex-col items-center w-full">
            <div class="flex-col mb-8 mx-8">
                <a href="http://tinystudioar.com" target="_blank" class="block mx-auto">
                    <img src="/assets/tiny2.svg" alt="Tiny Studio" class="w-64 sm:w-100 h-auto">
                </a>
            </div>
            <div class="flex-col text-center p-2">
                <p>
                    © {{ date('Y') }} La Que Va. Todos los derechos reservados
                </p>
            </div>
        </div>
    </section>
</div>

<!-- Carrito flotante -->
<div id="floating-cart" class="fixed bottom-4 right-4 z-50 hidden">
    <div class="bg-red-600 text-white rounded-full p-4 shadow-lg cursor-pointer hover:bg-red-700 transition-colors">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 9.5M17 13v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6"></path>
        </svg>
        <span id="cart-count" class="absolute -top-2 -right-2 bg-yellow-400 text-red-600 rounded-full w-6 h-6 flex items-center justify-center text-sm font-bold">
            0
        </span>
    </div>
</div>

@push('scripts')
<script>
// Only execute this script on the home page
if (window.location.pathname === '/' || window.location.pathname === '/welcome' || document.getElementById('pizza-slider')) {
    console.log('Home page script loaded');
    
    // Datos de las pizzas
    const pizzaData = @json($pizzas);
    
    // Estado de autenticación
    const isAuthenticated = @json(auth()->check());

// Sistema de carrito integrado con backend
let cart = [];

// Función para obtener el token CSRF
function getCsrfToken() {
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!token) {
        console.error('CSRF token not found');
        return null;
    }
    return token;
}

// Función para obtener el conteo total del carrito
async function fetchCartCount() {
    try {
        const response = await fetch('/cart/count', {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            return data.count;
        }
    } catch (error) {
        console.error('Error al obtener el conteo del carrito:', error);
    }
    return 0;
}

// Función para obtener las cantidades actuales del carrito
async function fetchCartItems() {
    try {
        const response = await fetch('/cart/count', {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            const quantities = {};
            if (data.items) {
                data.items.forEach(item => {
                    quantities[item.id] = item.quantity;
                });
            }
            return quantities;
        }
    } catch (error) {
        console.error('Error al obtener los items del carrito:', error);
    }
    return {};
}

// Actualizar la visualización del carrito
async function updateCartDisplay() {
    const cartCount = document.getElementById('cart-count');
    const floatingCart = document.getElementById('floating-cart');
    
    try {
        const totalItems = await fetchCartCount();
        console.log('Total items in cart:', totalItems);
        
        if (totalItems > 0) {
            cartCount.textContent = totalItems;
            floatingCart.classList.remove('hidden');
        } else {
            floatingCart.classList.add('hidden');
        }
    } catch (error) {
        console.error('Error updating cart display:', error);
    }
}

// Actualizar la visualización de cantidad en las tarjetas
function updateQuantityDisplay(pizzaId, quantity) {
    const card = document.querySelector(`[data-pizza-id="${pizzaId}"]`);
    if (!card) {
        console.error(`Card not found for pizza ID: ${pizzaId}`);
        return;
    }
    
    const quantityDisplay = card.querySelector('.quantity-display');
    if (!quantityDisplay) {
        console.error(`Quantity display not found for pizza ID: ${pizzaId}`);
        return;
    }
    
    quantityDisplay.textContent = quantity;
    console.log(`Updated quantity display for pizza ${pizzaId}: ${quantity}`);
}

// Añadir pizza al carrito (integrado con backend)
async function addToCart(pizzaId) {
    const pizza = pizzaData.find(p => p.id === pizzaId);
    console.log('Adding pizza to cart:', pizzaId, pizza);
    
    const csrfToken = getCsrfToken();
    if (!csrfToken) {
        console.error('Cannot add to cart: CSRF token not found');
        return;
    }

    try {
        const response = await fetch('/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                pizza_id: pizzaId,
                quantity: 1
            })
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const responseData = await response.json();
        console.log('Add to cart response:', responseData);
        
        // Actualizar la interfaz solo si el servidor confirma el éxito
        if (responseData.success) {
            updateQuantityDisplay(pizzaId, responseData.quantity);
            // Forzar actualización del carrito
            await updateCartDisplay();
            console.log('Cart updated successfully after adding pizza');
        } else {
            console.error('Server responded with error:', responseData);
        }
    } catch (error) {
        console.error('Error adding to cart:', error);
        // Mostrar feedback al usuario
        alert('Error al agregar al carrito. Por favor, intenta nuevamente.');
    }
}

// Remover pizza del carrito (integrado con backend)
async function removeFromCart(pizzaId) {
    console.log('Removing pizza from cart:', pizzaId);
    
    const csrfToken = getCsrfToken();
    if (!csrfToken) {
        console.error('Cannot remove from cart: CSRF token not found');
        return;
    }
    
    try {
        const response = await fetch(`/cart/remove/${pizzaId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const responseData = await response.json();
        console.log('Remove from cart response:', responseData);
        
        // Actualizar la interfaz solo si el servidor confirma el éxito
        if (responseData.success) {
            // Si quantity es 0, el ítem fue eliminado completamente
            const newQuantity = responseData.quantity || 0;
            updateQuantityDisplay(pizzaId, newQuantity);
            // Forzar actualización del carrito
            await updateCartDisplay();
            console.log('Cart updated successfully after removing pizza');
        } else {
            console.error('Server responded with error:', responseData);
        }
    } catch (error) {
        console.error('Error removing from cart:', error);
        // Mostrar feedback al usuario
        alert('Error al eliminar del carrito. Por favor, intenta nuevamente.');
    }
}

// Event listeners
document.addEventListener('DOMContentLoaded', async function() {
    // Verificar si viene desde el carrito y hacer scroll al menú
    const urlParams = new URLSearchParams(window.location.search);
    const fromCart = urlParams.get('from-cart');
    
    // Cargar cantidades iniciales del carrito
    const cartQuantities = await fetchCartItems();
    Object.keys(cartQuantities).forEach(pizzaId => {
        updateQuantityDisplay(parseInt(pizzaId), cartQuantities[pizzaId]);
    });
    updateCartDisplay();
    
    // Si viene desde el carrito, hacer scroll al menú después de cargar
    if (fromCart || window.location.hash === '#pizza-slider') {
        setTimeout(() => {
            const pizzaSlider = document.getElementById('pizza-slider');
            if (pizzaSlider) {
                pizzaSlider.scrollIntoView({ behavior: 'smooth' });
            }
        }, 500);
    }
    
    // Increase buttons
    document.querySelectorAll('.increase-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (!this || !this.getAttribute) {
                console.error('Invalid element in increase button click handler');
                return;
            }
            const pizzaIdStr = this.getAttribute('data-pizza-id');
            if (!pizzaIdStr) {
                console.error('No pizza ID found on increase button');
                return;
            }
            const pizzaId = parseInt(pizzaIdStr);
            if (isNaN(pizzaId)) {
                console.error('Invalid pizza ID:', pizzaIdStr);
                return;
            }
            addToCart(pizzaId);
        });
    });
    
    // Decrease buttons
    document.querySelectorAll('.decrease-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (!this || !this.getAttribute) {
                console.error('Invalid element in decrease button click handler');
                return;
            }
            const pizzaIdStr = this.getAttribute('data-pizza-id');
            if (!pizzaIdStr) {
                console.error('No pizza ID found on decrease button');
                return;
            }
            const pizzaId = parseInt(pizzaIdStr);
            if (isNaN(pizzaId)) {
                console.error('Invalid pizza ID:', pizzaIdStr);
                return;
            }
            removeFromCart(pizzaId);
        });
    });
    
    // Logo animation
    const logo = document.querySelector('.logo-animated');
    if (logo) {
        logo.style.transform = 'scale(0.5) rotate(-180deg)';
        logo.style.opacity = '0';
        logo.style.transition = 'all 1s ease-out';
        
        setTimeout(() => {
            logo.style.transform = 'scale(1) rotate(0deg)';
            logo.style.opacity = '1';
        }, 200);
    }
    
    // Pizza cards animation
    const pizzaCards = document.querySelectorAll('.pizza-card');
    pizzaCards.forEach((card, index) => {
        card.style.transform = 'translateY(50px)';
        card.style.opacity = '0';
        card.style.transition = 'all 0.5s ease-out';
        
        setTimeout(() => {
            card.style.transform = 'translateY(0)';
            card.style.opacity = '1';
        }, 200 + (index * 100));
    });
});

// Floating cart click handler
const floatingCartElement = document.getElementById('floating-cart');
if (floatingCartElement) {
    floatingCartElement.addEventListener('click', function() {
        // Redirigir a la página del carrito
        window.location.href = '/cart';
    });
}

// User dropdown toggle
function toggleUserDropdown() {
    const dropdown = document.getElementById('user-dropdown');
    if (dropdown) {
        dropdown.classList.toggle('hidden');
    }
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('user-dropdown');
    const button = event.target.closest('button');
    
    if (dropdown && !dropdown.contains(event.target) && (!button || button.getAttribute('onclick') !== 'toggleUserDropdown()')) {
        dropdown.classList.add('hidden');
    }
});

} // End of home page check
else {
    console.log('Not on home page, skipping pizza script');
}
</script>
@endpush

@endsection
