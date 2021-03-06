<?php

namespace App\Exports;

use App\Models\LineaInvestigacion;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithProperties;

class SemillerosInvestigacionLineaInvestigacionExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithProperties, WithColumnFormatting, WithTitle
{

    public function __construct()
    {
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return LineaInvestigacion::select('lineas_investigacion.*', 'grupos_investigacion.nombre as nombre_grupo', 'semilleros_investigacion.nombre as nombre_semillero', 'centros_formacion.codigo as codigo_centro', 'centros_formacion.nombre as nombre_centro')
            ->join('semillero_investigacion_linea_investigacion', 'lineas_investigacion.id', 'semillero_investigacion_linea_investigacion.linea_investigacion_id')
            ->join('semilleros_investigacion', 'semillero_investigacion_linea_investigacion.semillero_investigacion_id', 'semilleros_investigacion.id')
            ->join('grupos_investigacion', 'lineas_investigacion.grupo_investigacion_id', 'grupos_investigacion.id')
            ->join('centros_formacion', 'grupos_investigacion.centro_formacion_id', 'centros_formacion.id')
            ->get();
    }

    /**
     * @var Invoice $lineaInvestigacion
     */
    public function map($lineaInvestigacion): array
    {
        return [
            $lineaInvestigacion->codigo_centro,
            $lineaInvestigacion->nombre_centro,
            $lineaInvestigacion->nombre_grupo,
            $lineaInvestigacion->nombre_semillero,
            $lineaInvestigacion->nombre,
        ];
    }

    public function headings(): array
    {
        return [
            'C??digo del centro de formaci??n',
            'Centro de formaci??n',
            'Grupo de investigaci??n',
            'Semillero de investigaci??n',
            'Nombre de la l??nea de investigaci??n',
        ];
    }

    public function columnFormats(): array
    {
        return [];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Semilleros inv - Lineas de investigaci??n articulados';
    }

    public function properties(): array
    {
        return [
            'title' => 'Semilleros inv - Lineas de investigaci??n articulados',
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
