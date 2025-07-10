<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Http\Response;

class ExcelExportService
{
    /**
     * Export data to CSV format (compatible with Excel)
     */
    public function exportToCsv(Collection $data, array $headers, string $filename): Response
    {
        $output = fopen('php://output', 'w');
        
        // Write BOM for UTF-8 to ensure proper encoding in Excel
        fwrite($output, "\xEF\xBB\xBF");
        
        // Write headers
        fputcsv($output, $headers, ';'); // Use semicolon separator for better Excel compatibility
        
        // Write data
        foreach ($data as $row) {
            fputcsv($output, $row, ';');
        }
        
        fclose($output);
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
            'Pragma' => 'public',
        ];
        
        return response()->stream(function() use ($data, $headers, $filename) {
            $output = fopen('php://output', 'w');
            
            // Write BOM for UTF-8
            fwrite($output, "\xEF\xBB\xBF");
            
            // Write headers
            fputcsv($output, $headers, ';');
            
            // Write data
            foreach ($data as $row) {
                fputcsv($output, $row, ';');
            }
            
            fclose($output);
        }, 200, $headers);
    }
    
    /**
     * Format pizza data for export
     */
    public function formatPizzasData($pizzas): array
    {
        $data = [];
        $headers = [
            'ID',
            'Nombre',
            'Categoría',
            'Descripción',
            'Precio',
            'Ingredientes',
            'Disponible',
            'Destacado',
            'Tiempo de Preparación',
            'Fecha de Creación',
        ];
        
        foreach ($pizzas as $pizza) {
            $data[] = [
                $pizza->id,
                $pizza->name,
                $pizza->category->name ?? 'Sin categoría',
                $pizza->description,
                '$' . number_format($pizza->price, 2),
                implode(', ', $pizza->ingredients ?? []),
                $pizza->available ? 'Sí' : 'No',
                $pizza->featured ? 'Sí' : 'No',
                $pizza->preparation_time . ' min',
                $pizza->created_at->format('d/m/Y H:i'),
            ];
        }
        
        return ['headers' => $headers, 'data' => $data];
    }
    
    /**
     * Format customer data for export
     */
    public function formatCustomersData($customers): array
    {
        $data = [];
        $headers = [
            'ID',
            'Nombre',
            'Teléfono',
            'Email',
            'Dirección',
            'Barrio',
            'Cliente Frecuente',
            'Total Pedidos',
            'Fecha de Registro',
        ];
        
        foreach ($customers as $customer) {
            $data[] = [
                $customer->id,
                $customer->name,
                $customer->phone,
                $customer->email,
                $customer->address,
                $customer->neighborhood,
                $customer->frequent_customer ? 'Sí' : 'No',
                $customer->orders->count(),
                $customer->created_at->format('d/m/Y H:i'),
            ];
        }
        
        return ['headers' => $headers, 'data' => $data];
    }
    
    /**
     * Format category data for export
     */
    public function formatCategoriesData($categories): array
    {
        $data = [];
        $headers = [
            'ID',
            'Nombre',
            'Descripción',
            'Activa',
            'Orden',
            'Total Pizzas',
            'Pizzas Activas',
            'Fecha de Creación',
        ];
        
        foreach ($categories as $category) {
            $data[] = [
                $category->id,
                $category->name,
                $category->description,
                $category->active ? 'Sí' : 'No',
                $category->sort_order,
                $category->pizzas->count(),
                $category->pizzas->where('available', true)->count(),
                $category->created_at->format('d/m/Y H:i'),
            ];
        }
        
        return ['headers' => $headers, 'data' => $data];
    }
    
    /**
     * Format order data for export
     */
    public function formatOrdersData($orders): array
    {
        $data = [];
        $headers = [
            'ID',
            'Número de Orden',
            'Cliente',
            'Estado',
            'Método Entrega',
            'Método Pago',
            'Subtotal',
            'Costo de Entrega',
            'Total',
            'Notas',
            'Fecha de Creación',
        ];
        
        foreach ($orders as $order) {
            $data[] = [
                $order->id,
                $order->order_number,
                $order->customer->name,
                $order->status,
                $order->delivery_method,
                $order->payment_method,
                '$' . number_format($order->subtotal, 2),
                '$' . number_format($order->delivery_fee, 2),
                '$' . number_format($order->total, 2),
                $order->notes ?? 'N/A',
                $order->created_at->format('d/m/Y H:i'),
            ];
        }
        
        return ['headers' => $headers, 'data' => $data];
    }
}
