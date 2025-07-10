<?php

namespace App\Exports;

use App\Models\Pizza;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PizzaExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Pizza::with('category')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
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
    }

    /**
     * @param mixed $pizza
     * @return array
     */
    public function map($pizza): array
    {
        return [
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
