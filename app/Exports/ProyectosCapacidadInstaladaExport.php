<?php

namespace App\Exports;

use App\Models\ProyectoCapacidadInstalada;
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

class ProyectosCapacidadInstaladaExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithProperties, WithColumnFormatting, WithTitle
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
        return ProyectoCapacidadInstalada::orderBy('id')->get();
    }

    /**
     * @var Invoice $proyecto
     */
    public function map($proyectoCapacidadInstalada): array
    {
        return [
            $proyectoCapacidadInstalada->semilleroInvestigacion->lineaInvestigacion->grupoInvestigacion->centroFormacion->codigo,
            $proyectoCapacidadInstalada->semilleroInvestigacion->lineaInvestigacion->grupoInvestigacion->centroFormacion->nombre,
            $proyectoCapacidadInstalada->codigo,
            $proyectoCapacidadInstalada->titulo,
            $proyectoCapacidadInstalada->semilleroInvestigacion->nombre,
            $proyectoCapacidadInstalada->semilleroInvestigacion->lineaInvestigacion->nombre,
            $proyectoCapacidadInstalada->subtipoProyectoCapacidadInstalada->tipoProyectoCapacidadInstalada->tipo,
            $proyectoCapacidadInstalada->subtipoProyectoCapacidadInstalada->subtipo,
            $proyectoCapacidadInstalada->estado_proyecto,
            $proyectoCapacidadInstalada->redConocimiento->nombre,
            $proyectoCapacidadInstalada->actividadEconomica->nombre,
            $proyectoCapacidadInstalada->disciplinaSubareaConocimiento->subareaConocimiento->areaConocimiento->nombre,
            $proyectoCapacidadInstalada->disciplinaSubareaConocimiento->subareaConocimiento->nombre,
            $proyectoCapacidadInstalada->disciplinaSubareaConocimiento->nombre,
            $proyectoCapacidadInstalada->beneficiados_text,
            $proyectoCapacidadInstalada->fecha_ejecucion,
            $proyectoCapacidadInstalada->planteamiento_problema,
            $proyectoCapacidadInstalada->justificacion,
            $proyectoCapacidadInstalada->objetivo_general,
            $proyectoCapacidadInstalada->metodologia,
            $proyectoCapacidadInstalada->infraestructura_desarrollo_proyecto,
            $proyectoCapacidadInstalada->materiales_formacion_a_usar,
            $proyectoCapacidadInstalada->conclusiones,
            $proyectoCapacidadInstalada->bibliografia,
            $this->mapIntegrantes($proyectoCapacidadInstalada->integrantes),
        ];
    }

    public function headings(): array
    {
        return [
            'C??digo del centro de formaci??n',
            'Centro de formaci??n',
            'C??digo del proyecto',
            'T??tulo',
            'Nombre del semillero de investigaci??n relacionado',
            'Nombre de la l??nea de investigaci??n relacionada',
            'Tipo de proyecto',
            'Subtipo de proyecto',
            'Estado del proyecto',
            'Red de conocimiento',
            'Actividad econ??mica',
            '??rea de conocimiento',
            'Sub??rea de conocimiento',
            'Disciplina',
            'El proyecto beneficiar?? a',
            'Fecha de ejecuci??n',
            'Planteamiento delv problema',
            'Justificaci??n',
            'Objetivo general',
            'Metodolog??a',
            'Infraestructura para el desarrollo del proyecto',
            'Materiales de formaci??n a utilizar',
            'Conclusiones ',
            'Bibliograf??a',
            'Integrantes',
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
        return 'Proyectos capacidad instalada';
    }

    public function properties(): array
    {
        return [
            'title' => 'Proyectos capacidad instalada',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],
        ];
    }

    private function mapIntegrantes($integrantes)
    {
        $mapIntegrantes = [];

        foreach ($integrantes as $integrante) {
            array_push($mapIntegrantes, [
                'nombre' => strtr(utf8_decode($integrante->nombre), utf8_decode('??????????????????????????????????????????????????????????????????????????????????????????????????????'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY'),
                'documento' => $integrante->numero_documento,
                'vinculacion' => $integrante->tipo_vinculacion_text,
                'meses' => $integrante->pivot->cantidad_meses,
                'horas' => $integrante->pivot->cantidad_horas,
            ]);
        }
        return $mapIntegrantes;
    }
}
