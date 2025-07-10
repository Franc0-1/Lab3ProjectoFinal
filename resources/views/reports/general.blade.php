<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte General - La Que Va</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #dc2626;
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background-color: #f3f4f6;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
        }
        .stat-card h3 {
            color: #dc2626;
            margin: 0 0 10px 0;
            font-size: 16px;
        }
        .stat-value {
            font-size: 24px;
            font-weight: bold;
            color: #059669;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .table th {
            background-color: #dc2626;
            color: white;
            font-weight: bold;
        }
        .table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .section {
            margin-bottom: 40px;
            page-break-inside: avoid;
        }
        .section h2 {
            color: #dc2626;
            border-bottom: 2px solid #dc2626;
            padding-bottom: 10px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .highlight {
            background-color: #fef3c7;
        }
        .chart-placeholder {
            height: 200px;
            background-color: #f3f4f6;
            border: 1px solid #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #666;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte General del Sistema</h1>
        <p><strong>La Que Va - Pizzería</strong></p>
        <p>Fecha de generación: {{ date('d/m/Y H:i') }}</p>
        <p>Pasaje Necochea 2035, Resistencia, Chaco</p>
    </div>

    <!-- Estadísticas Generales -->
    <div class="section">
        <h2>Estadísticas Generales</h2>
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total de Órdenes</h3>
                <div class="stat-value">{{ $stats['total_orders'] }}</div>
            </div>
            <div class="stat-card">
                <h3>Ingresos Totales</h3>
                <div class="stat-value">${{ number_format($stats['total_revenue'], 2) }}</div>
            </div>
            <div class="stat-card">
                <h3>Promedio por Orden</h3>
                <div class="stat-value">${{ number_format($stats['average_order'], 2) }}</div>
            </div>
            <div class="stat-card">
                <h3>Órdenes Este Mes</h3>
                <div class="stat-value">{{ $stats['orders_this_month'] }}</div>
            </div>
            <div class="stat-card">
                <h3>Ingresos Este Mes</h3>
                <div class="stat-value">${{ number_format($stats['revenue_this_month'], 2) }}</div>
            </div>
            <div class="stat-card">
                <h3>Clientes Totales</h3>
                <div class="stat-value">{{ $customers->count() }}</div>
            </div>
        </div>
    </div>

    <!-- Resumen de Órdenes por Estado -->
    <div class="section">
        <h2>Órdenes por Estado</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Estado</th>
                    <th>Cantidad</th>
                    <th>Porcentaje</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stats['orders_by_status'] as $status => $count)
                    <tr>
                        <td>{{ ucfirst($status) }}</td>
                        <td>{{ $count }}</td>
                        <td>{{ number_format(($count / $stats['total_orders']) * 100, 2) }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Top Cliente -->
    @if($stats['top_customer'])
    <div class="section">
        <h2>Cliente Más Frecuente</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Total de Órdenes</th>
                    <th>Fecha de Registro</th>
                </tr>
            </thead>
            <tbody>
                <tr class="highlight">
                    <td>{{ $stats['top_customer']->name }}</td>
                    <td>{{ $stats['top_customer']->email }}</td>
                    <td>{{ $stats['top_customer']->orders_count }}</td>
                    <td>{{ $stats['top_customer']->created_at->format('d/m/Y') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    @endif

    <!-- Resumen de Pizzas -->
    <div class="section">
        <h2>Resumen de Pizzas</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Categoría</th>
                    <th>Cantidad de Pizzas</th>
                    <th>Precio Promedio</th>
                    <th>Pizzas Destacadas</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                    <tr>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->pizzas->count() }}</td>
                        <td>${{ number_format($category->pizzas->avg('price'), 2) }}</td>
                        <td>{{ $category->pizzas->where('featured', true)->count() }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pizzas Más Populares -->
    <div class="section">
        <h2>Pizzas Destacadas</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>Precio</th>
                    <th>Tiempo de Preparación</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pizzas->where('featured', true) as $pizza)
                    <tr class="highlight">
                        <td>{{ $pizza->name }}</td>
                        <td>{{ $pizza->category->name ?? 'Sin categoría' }}</td>
                        <td>${{ number_format($pizza->price, 2) }}</td>
                        <td>{{ $pizza->preparation_time }} min</td>
                        <td>{{ $pizza->available ? 'Disponible' : 'No disponible' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Clientes Frecuentes -->
    <div class="section">
        <h2>Clientes Frecuentes</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Órdenes</th>
                    <th>Fecha de Registro</th>
                </tr>
            </thead>
            <tbody>
                @foreach($customers->where('frequent_customer', true)->take(10) as $customer)
                    <tr>
                        <td>{{ $customer->name }}</td>
                        <td>{{ $customer->email }}</td>
                        <td>{{ $customer->phone ?? 'N/A' }}</td>
                        <td>{{ $customer->orders->count() }}</td>
                        <td>{{ $customer->created_at->format('d/m/Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Órdenes Recientes -->
    <div class="section">
        <h2>Órdenes Recientes (Últimas 10)</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Total</th>
                    <th>Items</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders->sortByDesc('created_at')->take(10) as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->customer->name ?? 'Cliente no registrado' }}</td>
                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ ucfirst($order->status) }}</td>
                        <td>${{ number_format($order->total, 2) }}</td>
                        <td>{{ $order->items->count() }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Notas adicionales -->
    <div class="section">
        <h2>Notas del Sistema</h2>
        <p><strong>Total de elementos en el sistema:</strong></p>
        <ul>
            <li>Pizzas: {{ $pizzas->count() }}</li>
            <li>Categorías: {{ $categories->count() }}</li>
            <li>Clientes: {{ $customers->count() }}</li>
            <li>Órdenes: {{ $orders->count() }}</li>
        </ul>
        
        <p><strong>Estado del sistema:</strong></p>
        <ul>
            <li>Pizzas disponibles: {{ $pizzas->where('available', true)->count() }}</li>
            <li>Pizzas destacadas: {{ $pizzas->where('featured', true)->count() }}</li>
            <li>Clientes frecuentes: {{ $customers->where('frequent_customer', true)->count() }}</li>
        </ul>
    </div>

    <div class="footer">
        <p>Generado por el sistema de gestión de La Que Va</p>
        <p>Este reporte contiene información confidencial del negocio</p>
        <p>Para más información, contactar: info@laqueva.com</p>
    </div>
</body>
</html>
