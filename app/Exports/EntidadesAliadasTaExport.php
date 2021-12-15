<?php

namespace App\Exports;

use App\Models\Convocatoria;
use App\Models\EntidadAliada;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithProperties;

class EntidadesAliadasTaExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithProperties, WithColumnFormatting, WithTitle
{
    protected $convocatoria;

    public function __construct(Convocatoria $convocatoria)
    {
        $this->convocatoria = $convocatoria;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return EntidadAliada::select('entidades_aliadas.*', 'entidad_aliada_ta.soporte_convenio', 'entidad_aliada_ta.fecha_inicio_convenio', 'entidad_aliada_ta.fecha_fin_convenio', 'proyectos.id as proyecto_id')->join('entidad_aliada_ta', 'entidades_aliadas.id', 'entidad_aliada_ta.entidad_aliada_id')->join('proyectos', 'entidades_aliadas.proyecto_id', 'proyectos.id')->where('proyectos.linea_programatica_id', 5)->whereNotIn('proyectos.id', [1052, 1113])->get();
    }

    /**
     * @var Invoice $entidadAliada
     */
    public function map($entidadAliada): array
    {
        return [
            'SGPS-' . ($entidadAliada->proyecto_id + 8000),
            $entidadAliada->tipo,
            $entidadAliada->nombre,
            $entidadAliada->naturaleza,
            $entidadAliada->tipo_empresa,
            $entidadAliada->nit,
            config('app.url') . ' /convocatorias/' . $this->convocatoria->id . '/proyectos/' . $entidadAliada->proyecto_id . '/entidades-aliadas/' . $entidadAliada->id . '/soporte_convenio/download',
            $entidadAliada->fecha_inicio_convenio,
            $entidadAliada->fecha_fin_convenio,
        ];
    }

    public function headings(): array
    {
        return [
            'Código del proyecto',
            'Tipo de entidad',
            'Nombre',
            'Naturaleza',
            'Tipo de empresa',
            'NIT',
            'Url convenio',
            'Fecha de inicio de convenio',
            'Fecha de fin de convenio',
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
        return 'Entidades aliadas';
    }

    public function properties(): array
    {
        return [
            'title' => 'Entidades aliadas',
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
