<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithProperties;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\WithTitle;

class ProyectosCapacidadInstaladaProgramasFormacionImpactadosExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithProperties, WithColumnFormatting, WithTitle
{
    protected $datos;

    public function __construct()
    {
        // 
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return DB::table('proyecto_capacidad_programa_formacion')
            ->select('programas_formacion.nombre', 'proyectos_capacidad_instalada.id as id_proyecto')
            ->join('proyectos_capacidad_instalada', 'proyecto_capacidad_programa_formacion.proyecto_capacidad_instalada_id', 'proyectos_capacidad_instalada.id')
            ->join('programas_formacion', 'proyecto_capacidad_programa_formacion.programa_formacion_id', 'programas_formacion.id')
            ->orderBy('proyectos_capacidad_instalada.id')
            ->get();
    }

    /**
     * @var Invoice $proyecto
     */
    public function map($programaFormacion): array
    {
        return [
            'CAP-' . sprintf("%05s", $programaFormacion->id_proyecto),
            $programaFormacion->nombre,

        ];
    }

    public function headings(): array
    {
        return [
            'Código del proyecto',
            'Nombre del programa de formación',
        ];
    }

    public function columnFormats(): array
    {
        return [
            // 
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Programas de formación registro calificado';
    }

    public function properties(): array
    {
        return [
            'title' => 'Programas de formación registro calificado',
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
