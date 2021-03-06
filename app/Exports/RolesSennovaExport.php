<?php

namespace App\Exports;

use App\Models\ProyectoRolSennova;
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

class RolesSennovaExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithProperties, WithColumnFormatting, WithTitle
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
        return ProyectoRolSennova::whereIn('proyecto_id', $idproyectos)->whereNotIn('id', [1052, 1113])->with('convocatoriaRolSennova.rolSennova', 'convocatoriaRolSennova.lineaProgramatica',)->get();
    }

    /**
     * @var Invoice $rolSennova
     */
    public function map($rolSennova): array
    {
        $data = [
            $rolSennova->proyecto->convocatoria->descripcion,
            $rolSennova->proyecto->centroFormacion->regional->nombre,
            $rolSennova->proyecto->centroFormacion->codigo,
            $rolSennova->proyecto->centroFormacion->nombre,
            $rolSennova->proyecto->lineaProgramatica->codigo,
            $rolSennova->proyecto->codigo,
            $rolSennova->convocatoriaRolSennova->rolSennova->nombre,
            $rolSennova->convocatoriaRolSennova->lineaProgramatica->nombre,
            $rolSennova->descripcion,
            $rolSennova->numero_meses,
            $rolSennova->numero_roles,
            $rolSennova->convocatoriaRolSennova->experiencia,
            ucfirst($rolSennova->convocatoriaRolSennova->nivel_academico_formateado),
            $rolSennova->convocatoriaRolSennova->asignacion_mensual,
            $rolSennova->getTotalRolSennova(),
            $rolSennova->getRolAprobadoAttribute(),

        ];

        foreach ($rolSennova->proyectoRolesEvaluaciones as $rolEvaluacion) {
            $data[] = $rolEvaluacion->evaluacion->evaluador->nombre;
            $data[] = $rolEvaluacion->correcto ? 'Cumple' : 'No cumple';
            $data[] = $rolEvaluacion->comentario;
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'Convocatoria',
            'Regional',
            'C??digo Centro de formaci??n',
            'Centro de formaci??n',
            'L??nea program??tica',
            'C??digo proyecto',
            'Rol SENNOVA',
            'Linea program??tica',
            'Descripci??n',
            'N??mero de meses',
            'N??mero de roles',
            'Experiencia',
            'Nivel acad??mico',
            'Asignaci??n mensual',
            'Total',
            'Estado final',
            'Evaluador 1',
            'Evaluaci??n',
            'Comentario',
            'Evaluador 2',
            'Evaluaci??n',
            'Comentario',
            'Evaluador 3',
            'Evaluaci??n',
            'Comentario',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'N' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
            'O' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Resumen Roles SENNOVA';
    }

    public function properties(): array
    {
        return [
            'title' => 'Resumen Roles SENNOVA ' . $this->convocatoria->descripcion,
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
