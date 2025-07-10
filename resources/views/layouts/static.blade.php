<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'La Que Va') }} - @yield('title', 'Pizzería')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Styles only -->
    @vite(['resources/css/app.css'])
    
    <!-- Additional CSS -->
    @stack('styles')
</head>
<body class="bg-gray-50 font-sans antialiased">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <!-- Logo -->
                    <div class="flex-shrink-0">
                        <a href="{{ route('welcome') }}" class="flex items-center">
                            <img class="h-8 w-auto" src="/assets/logo.svg" alt="La Que Va">
                            <span class="ml-2 text-xl font-bold text-gray-900">La Que Va</span>
                        </a>
                    </div>

                    <!-- Desktop Navigation -->
                    <div class="hidden md:ml-6 md:flex md:space-x-8">
                        <a href="{{ route('welcome') }}" class="border-red-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Inicio
                        </a>
                        <a href="{{ route('reports.index') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Reportes
                        </a>
                        <a href="#" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Pizzas
                        </a>
                        <a href="#" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Clientes
                        </a>
                    </div>
                </div>

                <!-- Right side -->
                <div class="flex items-center">
                    <!-- Mobile menu button -->
                    <div class="md:hidden">
                        <button type="button" class="bg-white inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-red-500" aria-controls="mobile-menu" aria-expanded="false">
                            <span class="sr-only">Abrir menú principal</span>
                            <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>

                    <!-- User menu -->
                    <div class="hidden md:ml-4 md:flex md:items-center md:space-x-4">
                        @guest
                            <a href="{{ route('login') }}" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">
                                Iniciar Sesión
                            </a>
                            <a href="{{ route('register') }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                Registrarse
                            </a>
                        @else
                            <div class="relative">
                                <button class="flex items-center space-x-2 bg-white text-gray-800 px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-100" onclick="toggleDropdown()">
                                    <span class="bg-gray-600 rounded-full h-8 w-8 flex items-center justify-center text-white">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </span>
                                    <span>{{ Auth::user()->name }}</span>
                                </button>
                                <div id="dropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10">
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Perfil
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}" class="block">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Cerrar Sesión
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endguest
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div class="md:hidden hidden" id="mobile-menu">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <a href="{{ route('welcome') }}" class="bg-red-50 border-red-500 text-red-700 block px-3 py-2 rounded-md text-base font-medium">Inicio</a>
                <a href="{{ route('reports.index') }}" class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block px-3 py-2 rounded-md text-base font-medium">Reportes</a>
                <a href="#" class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block px-3 py-2 rounded-md text-base font-medium">Pizzas</a>
                <a href="#" class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block px-3 py-2 rounded-md text-base font-medium">Clientes</a>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <main class="min-h-screen">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">La Que Va</h3>
                    <p class="mt-4 text-base text-gray-300">
                        Las mejores pizzas de Resistencia, Chaco. Ingredientes frescos y sabores únicos.
                    </p>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">Contacto</h3>
                    <ul class="mt-4 space-y-4">
                        <li class="text-base text-gray-300">
                            📍 Pasaje Necochea 2035, Resistencia, Chaco
                        </li>
                        <li class="text-base text-gray-300">
                            📞 (362) 123-4567
                        </li>
                        <li class="text-base text-gray-300">
                            📧 info@laqueva.com
                        </li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">Horarios</h3>
                    <ul class="mt-4 space-y-4">
                        <li class="text-base text-gray-300">
                            🕐 Lun - Vie: 18:00 - 00:00
                        </li>
                        <li class="text-base text-gray-300">
                            🕐 Sáb - Dom: 19:00 - 01:00
                        </li>
                    </ul>
                </div>
            </div>
            <div class="mt-8 border-t border-gray-700 pt-8 text-center">
                <p class="text-base text-gray-400">
                    © {{ date('Y') }} La Que Va. Todos los derechos reservados.
                </p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    @stack('scripts')
    
    <script>
        // Mobile menu toggle
        const mobileMenuButton = document.querySelector('[aria-controls="mobile-menu"]');
        const mobileMenu = document.getElementById('mobile-menu');
        
        if (mobileMenuButton && mobileMenu) {
            mobileMenuButton.addEventListener('click', function() {
                const expanded = this.getAttribute('aria-expanded') === 'true';
                this.setAttribute('aria-expanded', !expanded);
                mobileMenu.classList.toggle('hidden');
            });
        }
        
        // User dropdown toggle
        function toggleDropdown() {
            const dropdown = document.getElementById('dropdown');
            if (dropdown) {
                dropdown.classList.toggle('hidden');
            }
        }
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('dropdown');
            const button = event.target.closest('button');
            
            if (dropdown && !dropdown.contains(event.target) && button?.getAttribute('onclick') !== 'toggleDropdown()') {
                dropdown.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
