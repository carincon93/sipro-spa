<?php

namespace App\Exports;

use App\Models\ProyectoPresupuesto;
use App\Models\Convocatoria;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithProperties;

class PresupuestosExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithProperties, WithColumnFormatting, WithTitle
{
    protected $datos;
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
        $idproyectos = explode(',', $this->convocatoria->proyectos->implode('id', ','));
        return ProyectoPresupuesto::whereIn('proyecto_id', $idproyectos)->with('convocatoriaPresupuesto.presupuestoSennova.tercerGrupoPresupuestal:id,nombre', 'convocatoriaPresupuesto.presupuestoSennova.segundoGrupoPresupuestal:id,nombre,codigo', 'convocatoriaPresupuesto.presupuestoSennova.usoPresupuestal:id,descripcion')->get();
    }

    /**
     * @var Invoice $presupuesto
     */
    public function map($presupuesto): array
    {
        return [
            $presupuesto->proyecto->convocatoria->descripcion,
            $presupuesto->proyecto->centroFormacion->regional->nombre,
            $presupuesto->proyecto->centroFormacion->codigo,
            $presupuesto->proyecto->centroFormacion->nombre,
            $presupuesto->proyecto->lineaProgramatica->codigo,
            $presupuesto->proyecto->codigo,
            $presupuesto->convocatoriaPresupuesto->presupuestoSennova->tercerGrupoPresupuestal->nombre,
            $presupuesto->convocatoriaPresupuesto->presupuestoSennova->segundoGrupoPresupuestal->nombre,
            $presupuesto->convocatoriaPresupuesto->presupuestoSennova->usoPresupuestal->nombre,
            $presupuesto->descripcion,
            $presupuesto->justificacion,
            $presupuesto->valor_total,
            ($presupuesto->proyectoPresupuestosEvaluaciones()->count() > 0) ? (($presupuesto->presupuesto_aprobado) ? 'SI' : 'NO') : 'Sin evaluar',

        ];
    }

    public function headings(): array
    {
        return [
            'Convocatoria',
            'Regional',
            'Código Centro de formación',
            'Centro de formación',
            'Línea programática',
            'Código proyecto',
            'Homologable 2018',
            'Rubro 2019',
            'Uso presupuestal',
            'Descripción',
            'Justificación',
            'Valor',
            'Aprobado',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'H' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Resumen Presupuestal';
    }

    public function properties(): array
    {
        return [
            'title' => 'Resumen Presupuestal ' . $this->convocatoria->descripcion,
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
