<?php

namespace App\Exports;

use App\Models\Convocatoria;
use App\Models\GrupoInvestigacion;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithProperties;

class GruposInvestigacionExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithProperties, WithColumnFormatting, WithTitle
{
    protected $convocatoria;

    public function __construct()
    {
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return GrupoInvestigacion::select('grupos_investigacion.*',  'centros_formacion.codigo as codigo_centro', 'centros_formacion.nombre as nombre_centro')
            ->distinct('grupos_investigacion.id')
            ->join('lineas_investigacion', 'grupos_investigacion.id', 'lineas_investigacion.grupo_investigacion_id')
            ->join('centros_formacion', 'grupos_investigacion.centro_formacion_id', 'centros_formacion.id')
            ->get();
    }

    /**
     * @var Invoice $grupoInvestigacion
     */
    public function map($grupoInvestigacion): array
    {
        return [
            $grupoInvestigacion->codigo_centro,
            $grupoInvestigacion->nombre_centro,
            $grupoInvestigacion->nombre,
            $grupoInvestigacion->acronimo,
            $grupoInvestigacion->email,
            $grupoInvestigacion->enlace_gruplac,
            $grupoInvestigacion->codigo_minciencias,
            $grupoInvestigacion->categoria_minciencias_formateado,
            $grupoInvestigacion->mision,
            $grupoInvestigacion->vision,
            $grupoInvestigacion->fecha_creacion_grupo,
            $grupoInvestigacion->nombre_lider_grupo,
            $grupoInvestigacion->email_contacto,
            $grupoInvestigacion->programa_nal_ctei_principal,
            $grupoInvestigacion->programa_nal_ctei_secundaria,
            $grupoInvestigacion->reconocimientos_grupo_investigacion,
            $grupoInvestigacion->objetivo_general,
            $grupoInvestigacion->objetivos_especificos,
            $grupoInvestigacion->link_propio_grupo,
            $grupoInvestigacion->formato_gic_f_020,
            $grupoInvestigacion->formato_gic_f_032,
        ];
    }

    public function headings(): array
    {
        return [
            'C??digo del centro de formaci??n',
            'Centro de formaci??n',
            'Nombre del grupo de investigaci??n',
            'Acr??nimo',
            'Correo electr??nico',
            'Enlace GrupLAC',
            'Codigo Minciencias',
            'Categor??a Minciencias',
            'Misi??n',
            'Visi??n',
            'Fecha de creacion del grupo',
            'Nombre l??der del grupo',
            'Correo de contacto',
            'Programa Nal. CTeI (Principal',
            'Programa Nal. CTeI (Secundaria)',
            'Reconocimientos del grupo de investigaci??n',
            'Objetivo general',
            'Objetivos especificos',
            'Link propio del grupo',
            'Formato GIC ??? F ??? 020',
            'Formato GIC ??? F ??? 032',
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
        return 'Grupos de investigaci??n';
    }

    public function properties(): array
    {
        return [
            'title' => 'Grupos de investigaci??n',
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
