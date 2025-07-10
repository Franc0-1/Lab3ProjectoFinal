<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Test CSRF Cart</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold mb-4">Test CSRF Cart</h1>
        
        <div class="mb-4">
            <p><strong>CSRF Token:</strong> <span id="csrf-token">{{ csrf_token() }}</span></p>
            <p><strong>Session ID:</strong> {{ session()->getId() }}</p>
            <p><strong>App Environment:</strong> {{ config('app.env') }}</p>
            <p><strong>Session Driver:</strong> {{ config('session.driver') }}</p>
            <p><strong>App URL:</strong> {{ config('app.url') }}</p>
        </div>
        
        <div class="mb-4">
            <button onclick="testCSRF()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Test CSRF
            </button>
            <button onclick="testCart()" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded ml-2">
                Test Cart Add
            </button>
            <button onclick="testCSRFPost()" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded ml-2 mt-2">
                Test CSRF POST
            </button>
        </div>
        
        <div id="results" class="mt-4 p-4 bg-gray-50 rounded"></div>
    </div>

    <script>
        function getCsrfToken() {
            return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        }

        async function testCSRF() {
            const results = document.getElementById('results');
            results.innerHTML = 'Probando CSRF...';
            
            try {
                const response = await fetch('/test-csrf', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': getCsrfToken()
                    }
                });
                
                const data = await response.json();
                results.innerHTML = '<h3>CSRF Test Results:</h3><pre>' + JSON.stringify(data, null, 2) + '</pre>';
            } catch (error) {
                results.innerHTML = '<p class="text-red-500">Error: ' + error.message + '</p>';
            }
        }

        async function testCart() {
            const results = document.getElementById('results');
            results.innerHTML = 'Probando agregar al carrito...';
            
            try {
                const response = await fetch('/cart/add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': getCsrfToken()
                    },
                    body: JSON.stringify({
                        pizza_id: 1,
                        quantity: 1
                    })
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                
                const data = await response.json();
                results.innerHTML = '<h3>Cart Add Results:</h3><pre>' + JSON.stringify(data, null, 2) + '</pre>';
            } catch (error) {
                results.innerHTML = '<p class="text-red-500">Error: ' + error.message + '</p>';
            }
        }

        async function testCSRFPost() {
            const results = document.getElementById('results');
            results.innerHTML = 'Probando CSRF POST...';
            
            try {
                const response = await fetch('/test-csrf-post', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': getCsrfToken()
                    },
                    body: JSON.stringify({
                        test_data: 'Hello World',
                        timestamp: new Date().toISOString()
                    })
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                
                const data = await response.json();
                results.innerHTML = '<h3>CSRF POST Results:</h3><pre>' + JSON.stringify(data, null, 2) + '</pre>';
            } catch (error) {
                results.innerHTML = '<p class="text-red-500">Error: ' + error.message + '</p>';
            }
        }
    </script>
</body>
</html>
