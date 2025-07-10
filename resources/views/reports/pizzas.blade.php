<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Pizzas - La Que Va</title>
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
        .featured {
            background-color: #fef3c7;
        }
        .available {
            color: #16a34a;
            font-weight: bold;
        }
        .unavailable {
            color: #dc2626;
            font-weight: bold;
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
        <h1>Reporte de Pizzas</h1>
        <p><strong>La Que Va - Pizzería</strong></p>
        <p>Fecha de generación: {{ date('d/m/Y H:i') }}</p>
        <p>Total de pizzas: {{ $pizzas->count() }}</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Precio</th>
                <th>Ingredientes</th>
                <th>Estado</th>
                <th>Destacado</th>
                <th>Tiempo Prep.</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pizzas as $pizza)
                <tr class="{{ $pizza->featured ? 'featured' : '' }}">
                    <td>{{ $pizza->id }}</td>
                    <td>{{ $pizza->name }}</td>
                    <td>{{ $pizza->category->name ?? 'Sin categoría' }}</td>
                    <td>${{ number_format($pizza->price, 2) }}</td>
                    <td>{{ implode(', ', $pizza->ingredients ?? []) }}</td>
                    <td class="{{ $pizza->available ? 'available' : 'unavailable' }}">
                        {{ $pizza->available ? 'Disponible' : 'No disponible' }}
                    </td>
                    <td>{{ $pizza->featured ? 'Sí' : 'No' }}</td>
                    <td>{{ $pizza->preparation_time }} min</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Generado por el sistema de gestión de La Que Va</p>
        <p>Pasaje Necochea 2035, Resistencia, Chaco</p>
    </div>
</body>
</html>
