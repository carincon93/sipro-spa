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
        return ProyectoRolSennova::whereIn('proyecto_id', $idproyectos)->with('convocatoriaRolSennova.rolSennova','convocatoriaRolSennova.lineaProgramatica',)->get();
    }

    /**
     * @var Invoice $rolSennova
     */
    public function map($rolSennova): array
    {
        return [
            $rolSennova->proyecto->convocatoria->descripcion,
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

        ];
    }

    public function headings(): array
    {
        return [
            'Convocatoria',
            'Código',
            'Rol Sennova',
            'Linea Programatica',
            'Descripción',
            'Número de meses',
            'Número de roles',
            'Experiencia',
            'Nivel académico',
            'Asignación mensual',
            'Total',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'J' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
            'K' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
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
