<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Clientes Frecuentes - La Que Va</title>
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
        .criteria-info {
            background-color: #f3f4f6;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .criteria-info h3 {
            color: #dc2626;
            margin-top: 0;
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
        .vip-customer {
            background-color: #fef3c7;
        }
        .super-frequent {
            background-color: #dcfce7;
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
        .total-spent {
            font-weight: bold;
            color: #059669;
        }
        .order-count {
            font-weight: bold;
            color: #dc2626;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Clientes Frecuentes</h1>
        <p><strong>La Que Va - Pizzería</strong></p>
        <p>Fecha de generación: {{ date('d/m/Y H:i') }}</p>
        <p>Total de clientes frecuentes: {{ $customers->count() }}</p>
    </div>

    <div class="criteria-info">
        <h3>Criterios de Selección</h3>
        <p><strong>Clientes incluidos:</strong> Aquellos con 3 o más órdenes</p>
        <p><strong>Ordenamiento:</strong> Por cantidad de órdenes (mayor a menor)</p>
        <p><strong>Período:</strong> Desde el registro del cliente hasta la fecha</p>
    </div>

    <!-- Estadísticas de Clientes Frecuentes -->
    <div class="section">
        <h2>Estadísticas Generales</h2>
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Clientes Frecuentes</h3>
                <div class="stat-value">{{ $customers->count() }}</div>
            </div>
            <div class="stat-card">
                <h3>Total Órdenes</h3>
                <div class="stat-value">{{ $customers->sum('orders_count') }}</div>
            </div>
            <div class="stat-card">
                <h3>Promedio Órdenes por Cliente</h3>
                <div class="stat-value">{{ number_format($customers->avg('orders_count'), 1) }}</div>
            </div>
            <div class="stat-card">
                <h3>Cliente Más Frecuente</h3>
                <div class="stat-value">{{ $customers->first()->orders_count ?? 0 }} órdenes</div>
            </div>
        </div>
    </div>

    <!-- Lista Principal de Clientes Frecuentes -->
    <div class="section">
        <h2>Lista de Clientes Frecuentes</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Posición</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Órdenes</th>
                    <th>Promedio por Orden</th>
                    <th>Fecha Registro</th>
                    <th>Última Orden</th>
                </tr>
            </thead>
            <tbody>
                @foreach($customers as $index => $customer)
                    @php
                        $totalSpent = $customer->orders->sum('total');
                        $avgPerOrder = $customer->orders_count > 0 ? $totalSpent / $customer->orders_count : 0;
                        $lastOrder = $customer->orders->sortByDesc('created_at')->first();
                        
                        $rowClass = '';
                        if ($customer->orders_count >= 10) {
                            $rowClass = 'vip-customer';
                        } elseif ($customer->orders_count >= 7) {
                            $rowClass = 'super-frequent';
                        }
                    @endphp
                    <tr class="{{ $rowClass }}">
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $customer->name }}</td>
                        <td>{{ $customer->email }}</td>
                        <td>{{ $customer->phone ?? 'N/A' }}</td>
                        <td class="order-count">{{ $customer->orders_count }}</td>
                        <td class="total-spent">${{ number_format($avgPerOrder, 2) }}</td>
                        <td>{{ $customer->created_at->format('d/m/Y') }}</td>
                        <td>{{ $lastOrder ? $lastOrder->created_at->format('d/m/Y') : 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Análisis por Categorías -->
    <div class="section">
        <h2>Análisis por Categorías</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Categoría</th>
                    <th>Rango de Órdenes</th>
                    <th>Cantidad</th>
                    <th>Porcentaje</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $categories = [
                        'VIP' => $customers->where('orders_count', '>=', 10)->count(),
                        'Super Frecuente' => $customers->whereBetween('orders_count', [7, 9])->count(),
                        'Frecuente' => $customers->whereBetween('orders_count', [5, 6])->count(),
                        'Regular' => $customers->whereBetween('orders_count', [3, 4])->count(),
                    ];
                    $total = $customers->count();
                @endphp
                
                <tr class="vip-customer">
                    <td><strong>VIP</strong></td>
                    <td>10+ órdenes</td>
                    <td>{{ $categories['VIP'] }}</td>
                    <td>{{ $total > 0 ? number_format(($categories['VIP'] / $total) * 100, 1) : 0 }}%</td>
                </tr>
                <tr class="super-frequent">
                    <td><strong>Super Frecuente</strong></td>
                    <td>7-9 órdenes</td>
                    <td>{{ $categories['Super Frecuente'] }}</td>
                    <td>{{ $total > 0 ? number_format(($categories['Super Frecuente'] / $total) * 100, 1) : 0 }}%</td>
                </tr>
                <tr>
                    <td><strong>Frecuente</strong></td>
                    <td>5-6 órdenes</td>
                    <td>{{ $categories['Frecuente'] }}</td>
                    <td>{{ $total > 0 ? number_format(($categories['Frecuente'] / $total) * 100, 1) : 0 }}%</td>
                </tr>
                <tr>
                    <td><strong>Regular</strong></td>
                    <td>3-4 órdenes</td>
                    <td>{{ $categories['Regular'] }}</td>
                    <td>{{ $total > 0 ? number_format(($categories['Regular'] / $total) * 100, 1) : 0 }}%</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Top 10 Clientes VIP -->
    @if($customers->where('orders_count', '>=', 10)->count() > 0)
    <div class="section">
        <h2>Top 10 Clientes VIP</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Órdenes</th>
                    <th>Total Gastado</th>
                    <th>Promedio por Orden</th>
                    <th>Días como Cliente</th>
                    <th>Frecuencia (días/orden)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($customers->where('orders_count', '>=', 10)->take(10) as $customer)
                    @php
                        $totalSpent = $customer->orders->sum('total');
                        $avgPerOrder = $customer->orders_count > 0 ? $totalSpent / $customer->orders_count : 0;
                        $daysAsCustomer = $customer->created_at->diffInDays(now());
                        $frequency = $daysAsCustomer > 0 ? $daysAsCustomer / $customer->orders_count : 0;
                    @endphp
                    <tr class="vip-customer">
                        <td>{{ $customer->name }}</td>
                        <td class="order-count">{{ $customer->orders_count }}</td>
                        <td class="total-spent">${{ number_format($totalSpent, 2) }}</td>
                        <td>${{ number_format($avgPerOrder, 2) }}</td>
                        <td>{{ $daysAsCustomer }}</td>
                        <td>{{ number_format($frequency, 1) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Análisis de Tendencias -->
    <div class="section">
        <h2>Análisis de Retención</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Métrica</th>
                    <th>Valor</th>
                    <th>Observación</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $recentCustomers = $customers->where('created_at', '>=', now()->subDays(30));
                    $activeCustomers = $customers->filter(function($customer) {
                        return $customer->orders->where('created_at', '>=', now()->subDays(30))->count() > 0;
                    });
                @endphp
                
                <tr>
                    <td>Clientes nuevos (últimos 30 días)</td>
                    <td>{{ $recentCustomers->count() }}</td>
                    <td>{{ number_format(($recentCustomers->count() / $customers->count()) * 100, 1) }}% del total</td>
                </tr>
                <tr>
                    <td>Clientes activos (últimos 30 días)</td>
                    <td>{{ $activeCustomers->count() }}</td>
                    <td>{{ number_format(($activeCustomers->count() / $customers->count()) * 100, 1) }}% del total</td>
                </tr>
                <tr>
                    <td>Promedio de días entre órdenes</td>
                    <td>{{ number_format($customers->avg(function($customer) {
                        $daysAsCustomer = $customer->created_at->diffInDays(now());
                        return $customer->orders_count > 0 ? $daysAsCustomer / $customer->orders_count : 0;
                    }), 1) }}</td>
                    <td>Frecuencia promedio de pedidos</td>
                </tr>
                <tr>
                    <td>Cliente más antiguo</td>
                    <td>{{ $customers->sortBy('created_at')->first()->created_at->format('d/m/Y') }}</td>
                    <td>{{ $customers->sortBy('created_at')->first()->created_at->diffInDays(now()) }} días</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Recomendaciones -->
    <div class="section">
        <h2>Recomendaciones de Fidelización</h2>
        <div style="background-color: #f9f9f9; padding: 15px; border-radius: 8px;">
            <h3>Estrategias Sugeridas:</h3>
            <ul>
                <li><strong>Programa VIP:</strong> Ofrecer descuentos especiales a clientes con 10+ órdenes</li>
                <li><strong>Programa de Puntos:</strong> Implementar sistema de puntos por cada orden</li>
                <li><strong>Ofertas Personalizadas:</strong> Promociones basadas en las pizzas más pedidas por cada cliente</li>
                <li><strong>Comunicación Directa:</strong> Newsletter o WhatsApp para clientes frecuentes</li>
                <li><strong>Reactivación:</strong> Contactar clientes que no han ordenado en más de 30 días</li>
            </ul>
            
            <h3>Métricas Clave:</h3>
            <ul>
                <li>{{ number_format(($customers->count() / \App\Models\Customer::count()) * 100, 1) }}% de todos los clientes son frecuentes</li>
                <li>Promedio de {{ number_format($customers->avg('orders_count'), 1) }} órdenes por cliente frecuente</li>
                <li>{{ $categories['VIP'] }} clientes VIP generan alto valor</li>
                <li>{{ $activeCustomers->count() }} clientes activos en el último mes</li>
            </ul>
        </div>
    </div>

    <div class="footer">
        <p>Generado por el sistema de gestión de La Que Va</p>
        <p>Reporte confidencial de análisis de clientes</p>
        <p>Pasaje Necochea 2035, Resistencia, Chaco</p>
    </div>
</body>
</html>
