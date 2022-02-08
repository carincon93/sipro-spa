<?php

namespace App\Exports;

use App\Models\Convocatoria;
use App\Models\ProyectoAnexo;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithProperties;

class AnexosExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithProperties, WithColumnFormatting, WithTitle
{
    protected $convocatoria;

    public function __construct(Convocatoria $convocatoria, $lineaProgramaticaId)
    {
        $this->convocatoria = $convocatoria;
        $this->lineaProgramaticaId = $lineaProgramaticaId;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return ProyectoAnexo::select('proyecto_anexo.*', 'proyectos.id as proyecto_id')
            ->join('proyectos', 'proyecto_anexo.proyecto_id', 'proyectos.id')
            ->where('proyectos.linea_programatica_id', $this->lineaProgramaticaId)
            ->whereNotIn('proyectos.id', [1052, 1113])
            ->get();
    }

    /**
     * @var Invoice $proyectoAnexo
     */
    public function map($proyectoAnexo): array
    {
        return [
            'SGPS-' . ($proyectoAnexo->proyecto_id + 8000),
            config('app.url') . ' convocatorias/' . $this->convocatoria->id . '/proyectos/' . $proyectoAnexo->proyecto_id . '/proyecto-anexos/' . $proyectoAnexo->id . '/download',
        ];
    }

    public function headings(): array
    {
        return [
            'Código del proyecto',
            'Enlace de descarga',
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
        return 'Anexos';
    }

    public function properties(): array
    {
        return [
            'title' => 'Anexos',
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
