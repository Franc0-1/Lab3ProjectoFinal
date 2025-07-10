<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="shortcut icon" href="/favicon.ico">
    
    <!-- Meta tags -->
    <meta name="description" content="La Que Va - Las mejores pizzas de Resistencia, Chaco. Pedí online y recibí en tu casa o retirá por nuestro local en Paseo Sur.">
    <meta name="keywords" content="pizza, resistencia, chaco, delivery, pedidos online, la que va">
    <meta name="author" content="La Que Va Pizzería">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://laqueva.com">
    <meta property="og:title" content="La Que Va - Pizzería">
    <meta property="og:description" content="Las mejores pizzas de Resistencia, Chaco">
    <meta property="og:image" content="/android-chrome-512x512.png">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://laqueva.com">
    <meta property="twitter:title" content="La Que Va - Pizzería">
    <meta property="twitter:description" content="Las mejores pizzas de Resistencia, Chaco">
    <meta property="twitter:image" content="/android-chrome-512x512.png">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik+Wet+Paint&family=Sora:wght@100..800&family=Unbounded:wght@200..900&display=swap" rel="stylesheet">
    
    <!-- React Icons CDN -->
    <script src="https://unpkg.com/react@18/umd/react.development.js"></script>
    <script src="https://unpkg.com/react-dom@18/umd/react-dom.development.js"></script>
    
    <title>La Que Va - Pizzería Online</title>
    
    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
    
    @stack('styles')
</head>
<body class="flex justify-center min-h-screen bg-yellow-50">
    @yield('content')
    
    @stack('scripts')
</body>
</html>
