<!DOCTYPE html>
<html>
<head>
    <title>Test Forgot Password Link</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .test-link { display: block; margin: 10px 0; padding: 10px; background: #f0f0f0; text-decoration: none; color: #333; border: 1px solid #ddd; }
        .test-link:hover { background: #e0e0e0; }
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>
    <h1>Diagnóstico del Enlace de Recuperación</h1>
    
    <div>
        <h2>Información del Sistema:</h2>
        <p><strong>URL Base:</strong> {{ config('app.url') }}</p>
        <p><strong>Ruta generada:</strong> {{ route('password.request') }}</p>
        <p><strong>Rutas disponibles:</strong></p>
        <ul>
            <li>password.request → {{ route('password.request') }}</li>
            <li>login → {{ route('login') }}</li>
        </ul>
    </div>
    
    <div>
        <h2>Enlaces de Prueba:</h2>
        <a href="{{ route('password.request') }}" class="test-link">1. Enlace con route helper</a>
        <a href="/forgot-password" class="test-link">2. Enlace directo /forgot-password</a>
        <a href="http://127.0.0.1:8000/forgot-password" class="test-link">3. Enlace completo</a>
        <a href="javascript:testNavigation()" class="test-link">4. Navegación con JavaScript</a>
    </div>
    
    <div id="console-output">
        <h3>Salida de Console:</h3>
        <div id="output"></div>
    </div>
    
    <script>
        function log(message) {
            const output = document.getElementById('output');
            output.innerHTML += '<div>' + new Date().toLocaleTimeString() + ': ' + message + '</div>';
            console.log(message);
        }
        
        function testNavigation() {
            log('Probando navegación con JavaScript...');
            try {
                window.location.href = '/forgot-password';
                log('Navegación iniciada');
            } catch (error) {
                log('Error: ' + error.message);
            }
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            log('Página cargada');
            log('URL actual: ' + window.location.href);
            
            // Interceptar todos los clicks en enlaces
            document.querySelectorAll('a').forEach(function(link, index) {
                if (link.getAttribute('href') !== 'javascript:testNavigation()') {
                    link.addEventListener('click', function(e) {
                        log('Click en enlace ' + (index + 1) + ': ' + this.href);
                        
                        // Verificar si el enlace funciona
                        if (this.href && this.href !== '#') {
                            log('Navegando a: ' + this.href);
                            // Permitir navegación normal
                        } else {
                            log('ERROR: Enlace sin href válido');
                            e.preventDefault();
                        }
                    });
                }
            });
            
            // Verificar que las rutas existen
            fetch('/forgot-password', {method: 'HEAD'})
                .then(response => {
                    if (response.ok) {
                        log('✓ Ruta /forgot-password existe y responde');
                    } else {
                        log('✗ Ruta /forgot-password no responde: ' + response.status);
                    }
                })
                .catch(error => {
                    log('✗ Error verificando ruta: ' + error.message);
                });
        });
    </script>
</body>
</html>
