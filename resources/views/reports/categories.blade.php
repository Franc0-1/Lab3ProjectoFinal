<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Categorías - La Que Va</title>
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
        .active {
            background-color: #dcfce7;
        }
        .inactive {
            background-color: #fee2e2;
        }
        .pizza-count {
            font-weight: bold;
            color: #059669;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Categorías</h1>
        <p><strong>La Que Va - Pizzería</strong></p>
        <p>Fecha de generación: {{ date('d/m/Y H:i') }}</p>
        <p>Total de categorías: {{ $categories->count() }}</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Cantidad de Pizzas</th>
                <th>Estado</th>
                <th>Fecha Creación</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $category)
                <tr class="{{ $category->active ?? true ? 'active' : 'inactive' }}">
                    <td>{{ $category->id }}</td>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->description ?? 'Sin descripción' }}</td>
                    <td class="pizza-count">{{ $category->pizzas->count() }}</td>
                    <td>{{ ($category->active ?? true) ? 'Activa' : 'Inactiva' }}</td>
                    <td>{{ $category->created_at->format('d/m/Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 30px;">
        <h3 style="color: #dc2626;">Detalle de Pizzas por Categoría</h3>
        @foreach($categories as $category)
            @if($category->pizzas->count() > 0)
                <div style="margin-bottom: 20px; page-break-inside: avoid;">
                    <h4 style="color: #374151; margin-bottom: 10px;">{{ $category->name }} ({{ $category->pizzas->count() }} pizzas)</h4>
                    <table class="table" style="font-size: 10px;">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Precio</th>
                                <th>Estado</th>
                                <th>Destacado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($category->pizzas as $pizza)
                                <tr>
                                    <td>{{ $pizza->name }}</td>
                                    <td>${{ number_format($pizza->price, 2) }}</td>
                                    <td>{{ $pizza->available ? 'Disponible' : 'No disponible' }}</td>
                                    <td>{{ $pizza->featured ? 'Sí' : 'No' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        @endforeach
    </div>

    <div class="footer">
        <p>Generado por el sistema de gestión de La Que Va</p>
        <p>Pasaje Necochea 2035, Resistencia, Chaco</p>
    </div>
</body>
</html>
