<?php

namespace App\Exports;

use App\Models\Convocatoria;
use App\Models\Evaluacion\Evaluacion;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithProperties;

class EvaluacionesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithProperties, WithColumnFormatting
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
        return Evaluacion::select('evaluaciones.*')->join('proyectos', 'evaluaciones.proyecto_id', 'proyectos.id')->where('proyectos.convocatoria_id', $this->convocatoria->id)->get();
    }

    /**
     * @var Invoice $evaluacion
     */
    public function map($evaluacion): array
    {
        return [
            $evaluacion->proyecto->centroFormacion->regional->nombre,
            $evaluacion->proyecto->centroFormacion->codigo,
            $evaluacion->proyecto->centroFormacion->nombre,
            $evaluacion->proyecto->codigo,
            $evaluacion->proyecto->lineaProgramatica->codigo,
            $evaluacion->evaluador->nombre,
            $evaluacion->evaluador->numero_documento,
            $evaluacion->evaluador->email,
            $evaluacion->proyecto->idi()->exists() ? $evaluacion->proyecto->estadoEvaluacionIdi($evaluacion->getTotalEvaluacionAttribute(), $evaluacion->getTotalRecomendacionesAttribute(), null)['estado'] : ($evaluacion->proyecto->culturaInnovacion()->exists() ? $evaluacion->proyecto->estadoEvaluacionCulturaInnovacion($evaluacion->getTotalEvaluacionAttribute(), $evaluacion->getTotalRecomendacionesAttribute(), null)['estado'] : ($evaluacion->proyecto->ta()->exists() ? $evaluacion->proyecto->estado_evaluacion_ta['estado'] : ($evaluacion->proyecto->tp()->exists() ? $evaluacion->proyecto->estado_evaluacion_tp['estado'] : ($evaluacion->proyecto->servicioTecnologico()->exists() ? $evaluacion->proyecto->estadoEvaluacionServiciosTecnologicos($evaluacion->getTotalEvaluacionAttribute(), $evaluacion->getTotalRecomendacionesAttribute(), null)['estado'] : 'Sin información registrada')))),
            $evaluacion->proyecto->idi()->exists() ? $evaluacion->getTotalEvaluacionAttribute() : ($evaluacion->proyecto->culturaInnovacion()->exists() ? $evaluacion->getTotalEvaluacionAttribute() : ($evaluacion->proyecto->servicioTecnologico()->exists() ? $evaluacion->getTotalEvaluacionAttribute() : 'Sin información registrada')),
            $evaluacion->getTotalRecomendacionesAttribute(),
            $evaluacion->evaluacionCausalesRechazo()->count() > 0 ? $evaluacion->evaluacionCausalesRechazo()->get()->map(function ($causalesRechazo) {
                return  strtr(utf8_decode($causalesRechazo->causal_rechazo_formateado), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
            }) : 'Sin causal de rechazo',
            $evaluacion->getVerificarEstadoEvaluacionAttribute(),
        ];
    }

    public function headings(): array
    {
        return [
            'Regional',
            'Código Centro de formación',
            'Centro de formación',
            'Código proyecto',
            'Línea programática',
            'Evaluador',
            'Número de documento',
            'Correo electrónico',
            'Estado proyecto',
            'Puntaje',
            'Total recomendaciones',
            'Causales de rechazo',
            'Estado evaluación',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'O' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
            'P' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
            'Q' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
        ];
    }

    public function properties(): array
    {
        return [
            'title' => 'Evaluaciones Convocatoria SENNOVA',
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
