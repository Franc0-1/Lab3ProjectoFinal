<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Test CSRF</title>
</head>
<body>
    <h1>Test CSRF Token</h1>
    
    <div>
        <h2>Token Info:</h2>
        <p>CSRF Token: <span id="token-display">{{ csrf_token() }}</span></p>
        <p>Session ID: {{ session()->getId() }}</p>
    </div>
    
    <div>
        <h2>Test Buttons:</h2>
        <button onclick="testAddToCart()">Test Add to Cart</button>
        <button onclick="testRemoveFromCart()">Test Remove from Cart</button>
        <button onclick="testClearCart()">Test Clear Cart</button>
    </div>
    
    <div>
        <h2>Results:</h2>
        <pre id="results"></pre>
    </div>

    <script>
        function log(message) {
            const resultsDiv = document.getElementById('results');
            resultsDiv.textContent += new Date().toISOString() + ': ' + message + '\n';
            console.log(message);
        }

        function getCsrfToken() {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (!token) {
                log('ERROR: CSRF token not found');
                return null;
            }
            log('CSRF token found: ' + token.substring(0, 10) + '...');
            return token;
        }

        async function testAddToCart() {
            log('Testing Add to Cart...');
            const csrfToken = getCsrfToken();
            if (!csrfToken) return;

            try {
                const response = await fetch('/cart/add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        pizza_id: 1,
                        name: 'Test Pizza',
                        price: 1000,
                        image: 'test.jpg',
                        ingredients: ['Test']
                    })
                });

                log('Response status: ' + response.status);
                
                if (response.ok) {
                    const data = await response.json();
                    log('SUCCESS: ' + JSON.stringify(data));
                } else {
                    const errorText = await response.text();
                    log('ERROR: ' + response.status + ' - ' + errorText);
                }
            } catch (error) {
                log('EXCEPTION: ' + error.message);
            }
        }

        async function testRemoveFromCart() {
            log('Testing Remove from Cart...');
            const csrfToken = getCsrfToken();
            if (!csrfToken) return;

            try {
                const response = await fetch('/cart/remove/1', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });

                log('Response status: ' + response.status);
                
                if (response.ok) {
                    const data = await response.json();
                    log('SUCCESS: ' + JSON.stringify(data));
                } else {
                    const errorText = await response.text();
                    log('ERROR: ' + response.status + ' - ' + errorText);
                }
            } catch (error) {
                log('EXCEPTION: ' + error.message);
            }
        }

        async function testClearCart() {
            log('Testing Clear Cart...');
            const csrfToken = getCsrfToken();
            if (!csrfToken) return;

            try {
                const response = await fetch('/cart/clear', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });

                log('Response status: ' + response.status);
                
                if (response.ok) {
                    const data = await response.json();
                    log('SUCCESS: ' + JSON.stringify(data));
                } else {
                    const errorText = await response.text();
                    log('ERROR: ' + response.status + ' - ' + errorText);
                }
            } catch (error) {
                log('EXCEPTION: ' + error.message);
            }
        }

        // Auto-test on load
        window.onload = function() {
            log('Page loaded, testing CSRF token...');
            getCsrfToken();
        };
    </script>
</body>
</html>
