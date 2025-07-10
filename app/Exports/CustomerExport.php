<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CustomerExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Customer::all()->map(function ($customer) {
            return [
                'id' => $customer->id,
                'nombre' => $customer->name,
                'email' => $customer->email,
                'telefono' => $customer->phone,
                'direccion' => $customer->address,
                'creado' => $customer->created_at->format('Y-m-d H:i:s'),
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
            'Nombre',
            'Email',
            'Teléfono',
            'Dirección',
            'Fecha de Creación',
        ];
    }
}
