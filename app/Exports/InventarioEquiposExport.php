<?php

namespace App\Exports;

use App\Models\Proyecto;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithProperties;

class InventarioEquiposExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithProperties, WithColumnFormatting
{
    protected $proyecto;

    public function __construct(Proyecto $proyecto)
    {
        $this->proyecto = $proyecto;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->proyecto->inventarioEquipos;
    }

    /**
     * @var Invoice $invetarioEquipo
     */
    public function map($invetarioEquipo): array
    {
        return [
            $invetarioEquipo->proyecto->codigo,
            $invetarioEquipo->nombre,
            $invetarioEquipo->marca,
            $invetarioEquipo->serial,
            $invetarioEquipo->codigo_interno,
            $invetarioEquipo->fecha_adquisicion,
            $invetarioEquipo->estado_formateado,
            $invetarioEquipo->uso_st == '1' ? 'Si' : 'No',
            $invetarioEquipo->uso_otra_dependencia == '1' ? 'Si' : 'No',
            $invetarioEquipo->dependencia ? $invetarioEquipo->dependencia : 'N/A',
            $invetarioEquipo->descripcion,
            $invetarioEquipo->mantenimiento_prox_year == '1' ? 'Si' : 'No',
            $invetarioEquipo->calibracion_prox_year == '1' ? 'Si' : 'No',
        ];
    }

    public function headings(): array
    {
        return [
            'Código',
            'Nombre',
            'Marca',
            'Serial',
            'Código interno',
            'Fecha de adquisición',
            'Estado',
            '¿Uso exclusivo de Servicios tecnológicos?',
            '¿Otra dependencia que usa el equipo?',
            'Dependencia',
            'Descripción',
            '¿Para el próximo año el equipo necesita mantenimiento?',
            '¿Para el próximo año el equipo necesita calibración?',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'O' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
            'P' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
            'Q' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
        ];
    }

    public function properties(): array
    {
        return [
            'title' => 'Invetario equipos SGPS-' . $this->proyecto->codigo,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],
        ];
    }
}
