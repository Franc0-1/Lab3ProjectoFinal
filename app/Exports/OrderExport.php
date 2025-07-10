<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrderExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Order::with('customer')->get()->map(function ($order) {
            return [
                'id' => $order->id,
                'cliente' => $order->customer->name ?? 'Sin cliente',
                'total' => $order->total,
                'estado' => $order->status,
                'tipo' => $order->type,
                'notas' => $order->notes,
                'creado' => $order->created_at->format('Y-m-d H:i:s'),
            ];
        });
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Cliente',
            'Total',
            'Estado',
            'Tipo',
            'Notas',
            'Fecha de Creación',
        ];
    }
}
