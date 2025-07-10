<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Ventas - La Que Va</title>
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
        .period-info {
            background-color: #f3f4f6;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .period-info h3 {
            color: #dc2626;
            margin-top: 0;
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
        .total-amount {
            font-weight: bold;
            color: #059669;
        }
        .chart-section {
            margin: 20px 0;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Ventas por Período</h1>
        <p><strong>La Que Va - Pizzería</strong></p>
        <p>Fecha de generación: {{ date('d/m/Y H:i') }}</p>
    </div>

    <div class="period-info">
        <h3>Período del Reporte</h3>
        <p><strong>Desde:</strong> {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }}</p>
        <p><strong>Hasta:</strong> {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>
        <p><strong>Duración:</strong> {{ \Carbon\Carbon::parse($startDate)->diffInDays(\Carbon\Carbon::parse($endDate)) + 1 }} días</p>
    </div>

    <!-- Estadísticas del Período -->
    <div class="section">
        <h2>Estadísticas del Período</h2>
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total de Órdenes</h3>
                <div class="stat-value">{{ $salesData['total_orders'] }}</div>
            </div>
            <div class="stat-card">
                <h3>Ingresos Totales</h3>
                <div class="stat-value">${{ number_format($salesData['total_revenue'], 2) }}</div>
            </div>
            <div class="stat-card">
                <h3>Promedio por Orden</h3>
                <div class="stat-value">${{ number_format($salesData['average_order'], 2) }}</div>
            </div>
            <div class="stat-card">
                <h3>Promedio Diario</h3>
                <div class="stat-value">${{ number_format($salesData['total_revenue'] / ((\Carbon\Carbon::parse($startDate)->diffInDays(\Carbon\Carbon::parse($endDate))) + 1), 2) }}</div>
            </div>
        </div>
    </div>

    <!-- Ventas por Día -->
    <div class="section">
        <h2>Ventas por Día</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Órdenes</th>
                    <th>Ingresos</th>
                    <th>Promedio por Orden</th>
                </tr>
            </thead>
            <tbody>
                @foreach($salesData['orders_by_day'] as $date => $orderCount)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</td>
                        <td>{{ $orderCount }}</td>
                        <td class="total-amount">${{ number_format($salesData['revenue_by_day'][$date] ?? 0, 2) }}</td>
                        <td>${{ number_format(($salesData['revenue_by_day'][$date] ?? 0) / $orderCount, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Detalle de Órdenes -->
    <div class="section">
        <h2>Detalle de Órdenes del Período</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Total</th>
                    <th>Método Pago</th>
                    <th>Items</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->customer->name ?? 'Cliente no registrado' }}</td>
                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ ucfirst($order->status) }}</td>
                        <td class="total-amount">${{ number_format($order->total, 2) }}</td>
                        <td>{{ ucfirst($order->payment_method ?? 'N/A') }}</td>
                        <td>{{ $order->items->count() }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Resumen por Estado -->
    <div class="section">
        <h2>Resumen por Estado de Órdenes</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Estado</th>
                    <th>Cantidad</th>
                    <th>Total</th>
                    <th>Porcentaje</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders->groupBy('status') as $status => $statusOrders)
                    <tr>
                        <td>{{ ucfirst($status) }}</td>
                        <td>{{ $statusOrders->count() }}</td>
                        <td class="total-amount">${{ number_format($statusOrders->sum('total'), 2) }}</td>
                        <td>{{ number_format(($statusOrders->count() / $orders->count()) * 100, 2) }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pizzas Más Vendidas -->
    <div class="section">
        <h2>Pizzas Más Vendidas en el Período</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Pizza</th>
                    <th>Cantidad Vendida</th>
                    <th>Ingresos</th>
                    <th>Precio Promedio</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $pizzaSales = [];
                    foreach($orders as $order) {
                        foreach($order->items as $item) {
                            $pizzaName = $item->pizza->name ?? 'Pizza no disponible';
                            if(!isset($pizzaSales[$pizzaName])) {
                                $pizzaSales[$pizzaName] = ['quantity' => 0, 'revenue' => 0];
                            }
                            $pizzaSales[$pizzaName]['quantity'] += $item->quantity;
                            $pizzaSales[$pizzaName]['revenue'] += $item->price * $item->quantity;
                        }
                    }
                    
                    // Ordenar por cantidad vendida
                    arsort($pizzaSales);
                    $pizzaSales = array_slice($pizzaSales, 0, 10, true);
                @endphp
                
                @foreach($pizzaSales as $pizzaName => $data)
                    <tr>
                        <td>{{ $pizzaName }}</td>
                        <td>{{ $data['quantity'] }}</td>
                        <td class="total-amount">${{ number_format($data['revenue'], 2) }}</td>
                        <td>${{ number_format($data['revenue'] / $data['quantity'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Clientes Más Activos -->
    <div class="section">
        <h2>Clientes Más Activos en el Período</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Órdenes</th>
                    <th>Total Gastado</th>
                    <th>Promedio por Orden</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders->groupBy('customer_id')->sortByDesc(function($customerOrders) { return $customerOrders->count(); })->take(10) as $customerId => $customerOrders)
                    <tr>
                        <td>{{ $customerOrders->first()->customer->name ?? 'Cliente no registrado' }}</td>
                        <td>{{ $customerOrders->count() }}</td>
                        <td class="total-amount">${{ number_format($customerOrders->sum('total'), 2) }}</td>
                        <td>${{ number_format($customerOrders->avg('total'), 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Análisis de Tendencias -->
    <div class="section">
        <h2>Análisis de Tendencias</h2>
        <div class="chart-section">
            <h3>Observaciones del Período</h3>
            <ul>
                <li><strong>Mejor día:</strong> {{ \Carbon\Carbon::parse(array_search(max($salesData['revenue_by_day']), $salesData['revenue_by_day']))->format('d/m/Y') }} 
                    ({{ max($salesData['orders_by_day']) }} órdenes, ${{ number_format(max($salesData['revenue_by_day']), 2) }})</li>
                <li><strong>Día más activo:</strong> {{ \Carbon\Carbon::parse(array_search(max($salesData['orders_by_day']), $salesData['orders_by_day']))->format('d/m/Y') }} 
                    ({{ max($salesData['orders_by_day']) }} órdenes)</li>
                <li><strong>Promedio de órdenes por día:</strong> {{ number_format($salesData['total_orders'] / ((\Carbon\Carbon::parse($startDate)->diffInDays(\Carbon\Carbon::parse($endDate))) + 1), 2) }}</li>
                <li><strong>Días con ventas:</strong> {{ count($salesData['orders_by_day']) }} de {{ \Carbon\Carbon::parse($startDate)->diffInDays(\Carbon\Carbon::parse($endDate)) + 1 }} días</li>
            </ul>
        </div>
    </div>

    <div class="footer">
        <p>Generado por el sistema de gestión de La Que Va</p>
        <p>Reporte de ventas confidencial - Período: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>
        <p>Pasaje Necochea 2035, Resistencia, Chaco</p>
    </div>
</body>
</html>
