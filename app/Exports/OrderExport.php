<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrderExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Order::with('customer', 'items.pizza')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
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
    }

    /**
     * @param mixed $order
     * @return array
     */
    public function map($order): array
    {
        return [
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

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1 => ['font' => ['bold' => true]],
        ];
    }
}
