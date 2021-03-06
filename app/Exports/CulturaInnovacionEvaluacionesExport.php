<?php

namespace App\Exports;

use App\Models\Convocatoria;
use App\Models\Evaluacion\CulturaInnovacionEvaluacion;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithProperties;

class CulturaInnovacionEvaluacionesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithProperties, WithColumnFormatting, WithTitle
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
        return CulturaInnovacionEvaluacion::select('cultura_innovacion_evaluaciones.*')->join('evaluaciones', 'cultura_innovacion_evaluaciones.id', 'evaluaciones.id')->join('proyectos', 'evaluaciones.proyecto_id', 'proyectos.id')->where('proyectos.convocatoria_id', $this->convocatoria->id)->whereNotIn('proyectos.id', [1052, 1113])->get();
    }

    /**
     * @var Invoice $culturaInnovacionEvaluacion
     */
    public function map($culturaInnovacionEvaluacion): array
    {
        return [
            $culturaInnovacionEvaluacion->evaluacion->proyecto->centroFormacion->regional->nombre,
            $culturaInnovacionEvaluacion->evaluacion->proyecto->centroFormacion->codigo,
            $culturaInnovacionEvaluacion->evaluacion->proyecto->centroFormacion->nombre,
            $culturaInnovacionEvaluacion->evaluacion->proyecto->lineaProgramatica->codigo,
            $culturaInnovacionEvaluacion->evaluacion->evaluador->nombre,
            $culturaInnovacionEvaluacion->evaluacion->evaluador->numero_documento,
            $culturaInnovacionEvaluacion->evaluacion->evaluador->email,
            $culturaInnovacionEvaluacion->evaluacion->proyecto->codigo,
            $culturaInnovacionEvaluacion->evaluacion->proyecto->culturaInnovacion->titulo,
            $culturaInnovacionEvaluacion->titulo_comentario ? $culturaInnovacionEvaluacion->titulo_comentario : 'Cumple',
            $culturaInnovacionEvaluacion->video_comentario ? $culturaInnovacionEvaluacion->video_comentario : 'Cumple',
            $culturaInnovacionEvaluacion->resumen_comentario ? $culturaInnovacionEvaluacion->resumen_comentario : 'Cumple',
            $culturaInnovacionEvaluacion->problema_central_comentario ? $culturaInnovacionEvaluacion->problema_central_comentario : 'Cumple',
            $culturaInnovacionEvaluacion->objetivos_comentario ? $culturaInnovacionEvaluacion->objetivos_comentario : 'Cumple',
            $culturaInnovacionEvaluacion->metodologia_comentario ? $culturaInnovacionEvaluacion->metodologia_comentario : 'Cumple',
            $culturaInnovacionEvaluacion->entidad_aliada_comentario ? $culturaInnovacionEvaluacion->entidad_aliada_comentario : 'Cumple',
            $culturaInnovacionEvaluacion->resultados_comentario ? $culturaInnovacionEvaluacion->resultados_comentario : 'Cumple',
            $culturaInnovacionEvaluacion->productos_comentario ? $culturaInnovacionEvaluacion->productos_comentario : 'Cumple',
            $culturaInnovacionEvaluacion->cadena_valor_comentario ? $culturaInnovacionEvaluacion->cadena_valor_comentario : 'Cumple',
            $culturaInnovacionEvaluacion->analisis_riesgos_comentario ? $culturaInnovacionEvaluacion->analisis_riesgos_comentario : 'Cumple',
            $culturaInnovacionEvaluacion->ortografia_comentario ? $culturaInnovacionEvaluacion->ortografia_comentario : 'Cumple',
            $culturaInnovacionEvaluacion->redaccion_comentario ? $culturaInnovacionEvaluacion->redaccion_comentario : 'Cumple',
            $culturaInnovacionEvaluacion->normas_apa_comentario ? $culturaInnovacionEvaluacion->normas_apa_comentario : 'Cumple',
            $culturaInnovacionEvaluacion->justificacion_economia_naranja_comentario ? $culturaInnovacionEvaluacion->justificacion_economia_naranja_comentario : 'Cumple',
            $culturaInnovacionEvaluacion->justificacion_industria_4_comentario ? $culturaInnovacionEvaluacion->justificacion_industria_4_comentario : 'Cumple',
            $culturaInnovacionEvaluacion->bibliografia_comentario ? $culturaInnovacionEvaluacion->bibliografia_comentario : 'Cumple',
            $culturaInnovacionEvaluacion->fechas_comentario ? $culturaInnovacionEvaluacion->fechas_comentario : 'Cumple',
            $culturaInnovacionEvaluacion->justificacion_politica_discapacidad_comentario ? $culturaInnovacionEvaluacion->justificacion_politica_discapacidad_comentario : 'Cumple',
            $culturaInnovacionEvaluacion->actividad_economica_comentario ? $culturaInnovacionEvaluacion->actividad_economica_comentario : 'Cumple',
            $culturaInnovacionEvaluacion->area_conocimiento_comentario ? $culturaInnovacionEvaluacion->area_conocimiento_comentario : 'Cumple',
            $culturaInnovacionEvaluacion->tematica_estrategica_comentario ? $culturaInnovacionEvaluacion->tematica_estrategica_comentario : 'Cumple',

        ];
    }

    public function headings(): array
    {
        return [
            'Regional',
            'C??digo del centro de formaci??n',
            'Centro de formaci??n',
            'L??nea program??tica',
            'Evaluador',
            'N??mero de documento',
            'Correo electr??nico',
            'C??digo del proyecto',
            'T??tulo del proyecto',
            'T??tulo',
            'Video',
            'Resumen',
            'Problema central',
            'Objetivos',
            'Metodolog??a',
            'Entidad aliada',
            'Resultados',
            'Productos',
            'Cadena de valor',
            'An??lisis de riesgos',
            'Ortograf??a',
            'Redacci??n',
            'Normas APA',
            'Econom??a naranja',
            'Industria 4.0',
            'Bibliograf??a',
            'Fechas de ejecuci??n',
            'Pol??tica de discapacidad',
            'Actividad econ??mica',
            '??rea de conocimiento',
            'Tem??tica estrat??gica',
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
        return 'Cultura de la Innovaci??n';
    }

    public function properties(): array
    {
        return [
            'title' => 'Comentarios evaluaciones de Cultura de la Innovaci??n',
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
