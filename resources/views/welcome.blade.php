@extends('layouts.app')
@section('title', 'Bienvenido')
@section('content')

<!-- Hero Section -->
<section class="bg-gradient-to-r from-red-500 to-red-600 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
        <div class="text-center">
            <div class="mb-8">
                <img src="/assets/logo.svg" alt="La Que Va Logo" class="w-32 h-auto mx-auto mb-6">
            </div>
            <h1 class="text-4xl md:text-6xl font-bold mb-6">
                Bienvenido a<br><span class="text-yellow-300">La Que Va</span>
            </h1>
            <p class="text-xl md:text-2xl text-red-100 mb-8">
                Las mejores pizzas de Resistencia, Chaco
            </p>
            
            <!-- Status Component -->
            <div class="inline-block mb-8 px-6 py-3 bg-green-500 rounded-full border border-green-400">
                <p class="text-white font-semibold flex items-center">
                    <span class="w-3 h-3 bg-green-300 rounded-full mr-2 animate-pulse"></span>
                    🍕 Abierto - Haciendo pedidos
                </p>
            </div>
            
            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="#pizzas" class="bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-bold py-3 px-8 rounded-lg text-lg transition duration-300">
                    Ver Pizzas
                </a>
                <a href="{{ route('reports.index') }}" class="bg-transparent border-2 border-white hover:bg-white hover:text-red-600 text-white font-bold py-3 px-8 rounded-lg text-lg transition duration-300">
                    Reportes
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Pizza Section -->
<section id="pizzas" class="py-16 bg-yellow-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Nuestras Pizzas</h2>
            <p class="text-gray-600 mb-12">Deliciosas pizzas artesanales hechas con ingredientes frescos</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @php
                $pizzas = [
                    ['nombre' => 'MUZZARELLA', 'ingredientes' => 'Salsa, muzza, oregano', 'precio' => '5500', 'imagen' => 'pizza-4.webp'],
                    ['nombre' => 'MUZZA CON JAMON', 'ingredientes' => 'Salsa, muzza, jamon, oregano', 'precio' => '6000', 'imagen' => 'pizza-4.webp'],
                    ['nombre' => 'FUGAZZETA', 'ingredientes' => 'Salsa, muzza, cebolla, oregano', 'precio' => '6500', 'imagen' => 'pizza-4.webp'],
                    ['nombre' => 'NAPOLITANA', 'ingredientes' => 'Salsa, muzza, tomates, oregano, aceite de ajo', 'precio' => '6500', 'imagen' => 'pizza-4.webp'],
                    ['nombre' => 'NAPO CON JAMON', 'ingredientes' => 'Salsa, muzza, jamon, tomates, oregano', 'precio' => '7000', 'imagen' => 'pizza-4.webp'],
                    ['nombre' => 'FUGA CON JAMON', 'ingredientes' => 'Salsa, muzza, jamon, cebolla, oregano', 'precio' => '7000', 'imagen' => 'pizza-4.webp'],
                    ['nombre' => 'ESPECIAL', 'ingredientes' => 'Salsa, muzza, jamon, tomates, cebolla, oregano', 'precio' => '7000', 'imagen' => 'pizza-4.webp'],
                    ['nombre' => '2 MUZZA', 'ingredientes' => '2 Pizzas Muzzarella', 'precio' => '10000', 'imagen' => 'pizza-4.webp'],
                    ['nombre' => '1 MUZZA + 1 ESPECIAL', 'ingredientes' => '1 Pizza Muzzarella, 1 Pizza Especial', 'precio' => '12000', 'imagen' => 'pizza-4.webp'],
                ];
            @endphp
            @foreach ($pizzas as $pizza)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    <div class="h-48 bg-cover bg-center" style="background-image: url('/assets/pizzas/{{ $pizza['imagen'] }}')">
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $pizza['nombre'] }}</h3>
                        <p class="text-gray-600 mb-4">{{ $pizza['ingredientes'] }}</p>
                        <div class="flex items-center justify-between">
                            <span class="text-2xl font-bold text-red-600">${{ $pizza['precio'] }}</span>
                            <button class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md transition duration-300">
                                Pedir
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="text-3xl font-bold text-gray-900 mb-12">¿Por qué elegir La Que Va?</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="text-5xl mb-4">🍅</div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Ingredientes Frescos</h3>
                <p class="text-gray-600">Seleccionamos los mejores ingredientes para garantizar el sabor auténtico</p>
            </div>
            <div class="text-center">
                <div class="text-5xl mb-4">🚚</div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Entrega Rápida</h3>
                <p class="text-gray-600">Entregamos en toda Resistencia en menos de 30 minutos</p>
            </div>
            <div class="text-center">
                <div class="text-5xl mb-4">👨‍🍳</div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Recetas Artesanales</h3>
                <p class="text-gray-600">Preparadas con amor y siguiendo recetas tradicionales</p>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="py-16 bg-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="text-3xl font-bold text-gray-900 mb-12">Contáctanos</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white p-8 rounded-lg shadow-md">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Información de Contacto</h3>
                <div class="space-y-4">
                    <div class="flex items-center">
                        <span class="text-2xl mr-3">📍</span>
                        <p class="text-gray-600">Pasaje Necochea 2035, Resistencia, Chaco</p>
                    </div>
                    <div class="flex items-center">
                        <span class="text-2xl mr-3">📞</span>
                        <p class="text-gray-600">(362) 123-4567</p>
                    </div>
                    <div class="flex items-center">
                        <span class="text-2xl mr-3">📧</span>
                        <p class="text-gray-600">info@laqueva.com</p>
                    </div>
                </div>
            </div>
            <div class="bg-white p-8 rounded-lg shadow-md">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Horarios de Atención</h3>
                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="font-medium">Lunes - Viernes:</span>
                        <span class="text-gray-600">18:00 - 00:00</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Sábado - Domingo:</span>
                        <span class="text-gray-600">19:00 - 01:00</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
