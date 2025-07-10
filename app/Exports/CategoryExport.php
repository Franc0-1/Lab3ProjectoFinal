<?php

namespace App\Exports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CategoryExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Category::withCount('pizzas')->get()->map(function ($category) {
            return [
                'id' => $category->id,
                'nombre' => $category->name,
                'descripcion' => $category->description,
                'pizzas_count' => $category->pizzas_count,
                'creado' => $category->created_at->format('Y-m-d H:i:s'),
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
            'Cantidad de Pizzas',
            'Fecha de Creación',
        ];
    }
}
