@extends('layouts.app')

@section('title', 'Restablecer Contraseña')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="mx-auto h-12 w-auto text-center">
                <img class="mx-auto h-12 w-auto" src="/assets/logo.svg" alt="La Que Va">
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                ¿Olvidaste tu contraseña?
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                No te preocupes, te enviaremos un enlace para restablecerla.
            </p>
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded" role="alert">
                {{ session('status') }}
            </div>
        @endif
        
        <form class="mt-8 space-y-6" method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="rounded-md shadow-sm -space-y-px">
                <div>
                    <label for="email" class="sr-only">Correo electrónico</label>
                    <input id="email" name="email" type="email" autocomplete="email" required 
                           class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-red-500 focus:border-red-500 focus:z-10 sm:text-sm" 
                           placeholder="Ingresa tu correo electrónico" 
                           value="{{ old('email') }}">
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <button type="submit" 
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-red-500 group-hover:text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                        </svg>
                    </span>
                    Enviar enlace de restablecimiento
                </button>
            </div>

            <div class="text-center">
                <a href="{{ route('login') }}" class="font-medium text-red-600 hover:text-red-500">
                    Volver al inicio de sesión
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Asegurar que el token CSRF esté disponible
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        
        if (token) {
            // Verificar si ya existe un input hidden para el token
            let tokenInput = form.querySelector('input[name="_token"]');
            if (!tokenInput) {
                tokenInput = document.createElement('input');
                tokenInput.type = 'hidden';
                tokenInput.name = '_token';
                form.appendChild(tokenInput);
            }
            tokenInput.value = token;
            console.log('Token CSRF configurado:', token);
        } else {
            console.error('No se encontró el token CSRF en el meta tag');
        }
        
        // Agregar logging para debug
        form.addEventListener('submit', function(e) {
            console.log('Formulario enviado');
            console.log('Email:', document.getElementById('email').value);
            console.log('Action:', this.action);
            console.log('Method:', this.method);
            
            const tokenInput = form.querySelector('input[name="_token"]');
            if (tokenInput) {
                console.log('Token CSRF:', tokenInput.value);
            } else {
                console.error('No se encontró el input del token CSRF');
            }
            
            // Verificar FormData
            const formData = new FormData(form);
            console.log('FormData entries:');
            for (let pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
            }
        });
    });
</script>
@endpush
