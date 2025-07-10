<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Test Cart Buttons</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 800px; margin: 0 auto; }
        .test-section { margin: 20px 0; padding: 20px; border: 1px solid #ccc; border-radius: 5px; }
        button { padding: 10px 15px; margin: 5px; background: #007cba; color: white; border: none; border-radius: 3px; cursor: pointer; }
        button:hover { background: #005a8a; }
        .danger { background: #dc3545; }
        .danger:hover { background: #c82333; }
        .success { background: #28a745; }
        .success:hover { background: #218838; }
        #logs { background: #f8f9fa; padding: 15px; height: 300px; overflow-y: auto; border: 1px solid #ddd; border-radius: 3px; font-family: monospace; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🛒 Test de Botones del Carrito</h1>
        
        <div class="test-section">
            <h2>Estado del Carrito</h2>
            <p>Antes de usar los botones, abre la consola del navegador (F12) para ver los logs detallados.</p>
            
            <button onclick="testDebugCart()" class="success">Ver Estado del Carrito</button>
            <button onclick="testAddItem()">Agregar Item de Prueba</button>
        </div>
        
        <div class="test-section">
            <h2>Funciones de Prueba</h2>
            <button onclick="testUpdateQuantity()">Test Update Quantity</button>
            <button onclick="testClearCart()" class="danger">Test Clear Cart</button>
            <button onclick="testRemoveItem()" class="danger">Test Remove Item</button>
        </div>
        
        <div class="test-section">
            <h2>Logs en Tiempo Real</h2>
            <div id="logs"></div>
            <button onclick="clearLogs()">Limpiar Logs</button>
        </div>
    </div>

    <script>
        function log(message) {
            console.log(message);
            const logsDiv = document.getElementById('logs');
            const timestamp = new Date().toLocaleTimeString();
            logsDiv.innerHTML += `<div><strong>[${timestamp}]</strong> ${message}</div>`;
            logsDiv.scrollTop = logsDiv.scrollHeight;
        }

        function clearLogs() {
            document.getElementById('logs').innerHTML = '';
        }

        function testAddItem() {
            log('🍕 Agregando item de prueba al carrito...');
            
            fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                },
                body: JSON.stringify({
                    pizza_id: 1,
                    name: 'Pizza Test',
                    price: 1000,
                    image: 'test.jpg',
                    ingredients: ['Test', 'Ingredients']
                })
            })
            .then(response => response.json())
            .then(data => {
                log('✅ Item agregado: ' + JSON.stringify(data));
            })
            .catch(error => {
                log('❌ Error agregando item: ' + error);
            });
        }

        function testUpdateQuantity() {
            log('🔄 Testing updateQuantity...');
            
            // Simular función updateQuantityForm
            const updateQuantityForm = (pizzaId, newQuantity) => {
                log(`📝 updateQuantityForm called with pizzaId: ${pizzaId}, newQuantity: ${newQuantity}`);
                
                if (newQuantity < 1 || newQuantity > 10) {
                    log('❌ Cantidad inválida: ' + newQuantity);
                    alert('Cantidad inválida. Debe ser entre 1 y 10.');
                    return;
                }
                
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                log('🔑 CSRF Token encontrado: ' + (csrfToken ? 'SÍ' : 'NO'));
                
                if (!csrfToken) {
                    log('❌ ERROR: No se encontró el token CSRF');
                    alert('Error: Token CSRF no encontrado. Recarga la página.');
                    return;
                }
                
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/cart/update/${pizzaId}`;
                form.style.display = 'none';
                
                log('🏗️ Creando formulario para: ' + form.action);
                
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
                
                log('📤 Enviando formulario...');
                if (confirm('¿Enviar formulario al servidor?')) {
                    form.submit();
                } else {
                    log('❌ Envío cancelado por usuario');
                    document.body.removeChild(form);
                }
            };
            
            // Probar con pizza ID 1, cantidad 3
            updateQuantityForm(1, 3);
        }

        function testClearCart() {
            log('🧹 Testing clearCart...');
            
            if (!confirm('¿Estás seguro de que quieres vaciar el carrito?')) {
                log('❌ Cancelado por usuario');
                return;
            }
            
            fetch('/cart/clear', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                }
            })
            .then(response => response.json())
            .then(data => {
                log('✅ Clear cart response: ' + JSON.stringify(data));
            })
            .catch(error => {
                log('❌ Error clearing cart: ' + error);
            });
        }

        function testRemoveItem() {
            log('🗑️ Testing removeItem...');
            
            if (!confirm('¿Estás seguro de que quieres eliminar el item?')) {
                log('❌ Cancelado por usuario');
                return;
            }
            
            fetch('/cart/delete/1', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                }
            })
            .then(response => {
                log('📡 Response status: ' + response.status);
                log('📡 Response headers: ' + JSON.stringify(Object.fromEntries(response.headers)));
                
                if (!response.ok) {
                    return response.text().then(text => {
                        log('❌ Response not OK, body: ' + text);
                        throw new Error(`HTTP ${response.status}: ${text}`);
                    });
                }
                
                // Intentar parsear como JSON
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    return response.json();
                } else {
                    return response.text().then(text => {
                        log('❌ Response no es JSON, body: ' + text);
                        throw new Error('Response is not JSON: ' + text);
                    });
                }
            })
            .then(data => {
                log('✅ Remove item response: ' + JSON.stringify(data));
            })
            .catch(error => {
                log('❌ Error removing item: ' + error.message);
                console.error('Full error:', error);
            });
        }

        function testDebugCart() {
            log('🔍 Testing debug cart...');
            
            fetch('/cart/debug')
            .then(response => response.json())
            .then(data => {
                log('📊 Debug cart response: ' + JSON.stringify(data, null, 2));
            })
            .catch(error => {
                log('❌ Error debugging cart: ' + error);
            });
        }

        // Ejecutar debug automáticamente al cargar la página
        window.onload = function() {
            log('🚀 Página de prueba cargada');
            testDebugCart();
        };
    </script>
</body>
</html>
