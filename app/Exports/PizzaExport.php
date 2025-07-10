<?php

namespace App\Exports;

use App\Models\Pizza;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PizzaExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Pizza::with('category')->get()->map(function ($pizza) {
            return [
                'id' => $pizza->id,
                'nombre' => $pizza->name,
                'descripcion' => $pizza->description,
                'precio' => $pizza->price,
                'categoria' => $pizza->category->name ?? 'Sin categoría',
                'disponible' => $pizza->is_available ? 'Sí' : 'No',
                'creado' => $pizza->created_at->format('Y-m-d H:i:s'),
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
            'Descripción',
            'Precio',
            'Categoría',
            'Disponible',
            'Fecha de Creación',
        ];
    }
}
