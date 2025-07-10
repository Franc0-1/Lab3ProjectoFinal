<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Órdenes - La Que Va</title>
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
        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }
        .status-completed {
            background-color: #dcfce7;
            color: #166534;
        }
        .status-cancelled {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .status-processing {
            background-color: #dbeafe;
            color: #1e40af;
        }
        .total-amount {
            font-weight: bold;
            color: #059669;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .summary {
            margin-top: 20px;
            padding: 15px;
            background-color: #f3f4f6;
            border-radius: 8px;
        }
        .summary h3 {
            color: #dc2626;
            margin-top: 0;
        }
        .summary-item {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Órdenes</h1>
        <p><strong>La Que Va - Pizzería</strong></p>
        <p>Fecha de generación: {{ date('d/m/Y H:i') }}</p>
        <p>Total de órdenes: {{ $orders->count() }}</p>
    </div>

    <div class="summary">
        <h3>Resumen de Órdenes</h3>
        <div class="summary-item"><strong>Total de órdenes:</strong> {{ $orders->count() }}</div>
        <div class="summary-item"><strong>Total facturado:</strong> ${{ number_format($orders->sum('total'), 2) }}</div>
        <div class="summary-item"><strong>Promedio por orden:</strong> ${{ number_format($orders->avg('total'), 2) }}</div>
        <div class="summary-item"><strong>Órdenes completadas:</strong> {{ $orders->where('status', 'completed')->count() }}</div>
        <div class="summary-item"><strong>Órdenes pendientes:</strong> {{ $orders->where('status', 'pending')->count() }}</div>
        <div class="summary-item"><strong>Órdenes canceladas:</strong> {{ $orders->where('status', 'cancelled')->count() }}</div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Total</th>
                <th>Método Pago</th>
                <th>Tipo Entrega</th>
                <th>Items</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->customer->name ?? 'Cliente no registrado' }}</td>
                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td class="status-{{ $order->status }}">
                        @switch($order->status)
                            @case('pending')
                                Pendiente
                                @break
                            @case('processing')
                                Procesando
                                @break
                            @case('completed')
                                Completada
                                @break
                            @case('cancelled')
                                Cancelada
                                @break
                            @default
                                {{ ucfirst($order->status) }}
                        @endswitch
                    </td>
                    <td class="total-amount">${{ number_format($order->total, 2) }}</td>
                    <td>{{ ucfirst($order->payment_method ?? 'N/A') }}</td>
                    <td>{{ ucfirst($order->delivery_type ?? 'N/A') }}</td>
                    <td>{{ $order->items->count() }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 30px;">
        <h3 style="color: #dc2626;">Detalle de Items por Orden</h3>
        @foreach($orders->take(10) as $order)
            <div style="margin-bottom: 20px; page-break-inside: avoid;">
                <h4 style="color: #374151; margin-bottom: 10px;">
                    Orden #{{ $order->id }} - {{ $order->customer->name ?? 'Cliente no registrado' }} 
                    ({{ $order->created_at->format('d/m/Y H:i') }})
                </h4>
                <table class="table" style="font-size: 10px;">
                    <thead>
                        <tr>
                            <th>Pizza</th>
                            <th>Cantidad</th>
                            <th>Precio Unit.</th>
                            <th>Subtotal</th>
                            <th>Notas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>{{ $item->pizza->name ?? 'Pizza no disponible' }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>${{ number_format($item->price, 2) }}</td>
                                <td>${{ number_format($item->price * $item->quantity, 2) }}</td>
                                <td>{{ $item->notes ?? 'Sin notas' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div style="text-align: right; margin-top: 10px; font-weight: bold;">
                    Total: ${{ number_format($order->total, 2) }}
                </div>
            </div>
        @endforeach
        
        @if($orders->count() > 10)
            <p style="color: #666; font-style: italic;">
                Mostrando detalle de las primeras 10 órdenes. Total de órdenes: {{ $orders->count() }}
            </p>
        @endif
    </div>

    <div class="footer">
        <p>Generado por el sistema de gestión de La Que Va</p>
        <p>Pasaje Necochea 2035, Resistencia, Chaco</p>
    </div>
</body>
</html>
