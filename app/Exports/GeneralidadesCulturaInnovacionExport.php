<?php

namespace App\Exports;

use App\Models\Convocatoria;
use App\Models\CulturaInnovacion;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithProperties;

class GeneralidadesCulturaInnovacionExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithProperties, WithColumnFormatting, WithTitle
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
        return CulturaInnovacion::selectRaw("cultura_innovacion.*, CASE cultura_innovacion.muestreo
            WHEN '1' THEN 'Especies Nativas. (es la especie o subespecie taxonómica o variedad de animales cuya área de disposición geográfica se extiende al territorio nacional o a aguas jurisdiccionales colombianas o forma parte de los mismos comprendidas las especies o subespecies que migran temporalmente a ellos, siempre y cuando no se encuentren en el país o migren a él como resultado voluntario o involuntario de la actividad humana. Pueden ser silvestre, domesticada o escapada de domesticación incluyendo virus, viroides y similares)'
            WHEN '2' THEN 'Especies Introducidas. (son aquellas que no son nativas de Colombia y que ingresaron al país por intervención humana)'
            WHEN '3' THEN 'Recursos genéticos humanos y sus productos derivados'
            WHEN '4' THEN 'Intercambio de recursos genéticos y sus productos derivados, recursos biológicos que los contienen o los componentes asociados a estos. (son aquellas que realizan las comunidades indígenas, afroamericanas y locales de los Países Miembros de la Comunidad Andina entre sí y para su propio consumo, basadas en sus prácticas consuetudinarias)'
            WHEN '5' THEN 'Recurso biológico que involucren actividades de sistemática molecular, ecología molecular, evolución y biogeografía molecular (siempre que el recurso biológico se haya colectado en el marco de un permiso de recolección de especímenes de especies silvestres de la diversidad biológica con fines de investigación científica no comercial o provenga de una colección registrada ante el Instituto Alexander van Humboldt)'
            WHEN '6' THEN 'No aplica'
            END as muestreo,
            CASE cultura_innovacion.actividades_muestreo
            WHEN '1.1.1' THEN 'Separación de las unidades funcionales y no funcionales del ADN y el ARN, en todas las formas que se encuentran en la naturaleza.'
            WHEN '1.1.2' THEN 'Aislamiento de una o varias moléculas, entendidas estas como micro y macromoléculas, producidas por el metabolismo de un organismo.'
            WHEN '1.1.3' THEN 'Solicitar patente sobre una función o propiedad identificada de una molécula, que se ha aislado y purificado.'
            WHEN '1.1.4' THEN 'No logro identificar la actividad a desarrollar con la especie nativa'
            END as actividades_muestreo,
            CASE cultura_innovacion.objetivo_muestreo
            WHEN '1.2.1' THEN 'Investigación básica sin fines comerciales'
            WHEN '1.2.2' THEN 'Bioprospección en cualquiera de sus fases'
            WHEN '1.2.3' THEN 'Comercial o Industrial'
            END as objetivo_muestreo
            ")
            ->join('proyectos', 'cultura_innovacion.id', 'proyectos.id')
            ->where('proyectos.convocatoria_id', $this->convocatoria->id)
            ->whereNotIn('cultura_innovacion.id', [1052, 1113])
            ->get();
    }

    /**
     * @var Invoice $idi
     */
    public function map($culturaInnovacion): array
    {
        return [
            $this->convocatoria->descripcion,
            $culturaInnovacion->proyecto->centroFormacion->regional->nombre,
            $culturaInnovacion->proyecto->centroFormacion->codigo,
            $culturaInnovacion->proyecto->centroFormacion->nombre,
            $culturaInnovacion->proyecto->codigo,
            $culturaInnovacion->titulo,
            $culturaInnovacion->fecha_inicio,
            $culturaInnovacion->fecha_finalizacion,
            $culturaInnovacion->lineaInvestigacion->grupoInvestigacion->nombre,
            $culturaInnovacion->lineaInvestigacion->nombre,
            $culturaInnovacion->areaConocimiento->nombre,
            $culturaInnovacion->tematicaEstrategica->nombre,
            $culturaInnovacion->actividadEconomica->nombre,
            $culturaInnovacion->video,
            $culturaInnovacion->justificacion_industria_4,
            $culturaInnovacion->justificacion_economia_naranja,
            $culturaInnovacion->justificacion_politica_discapacidad,
            $culturaInnovacion->resumen,
            $culturaInnovacion->antecedentes,
            $culturaInnovacion->marco_conceptual,
            $culturaInnovacion->metodologia,
            $culturaInnovacion->propuesta_sostenibilidad,
            $culturaInnovacion->muestreo,
            $culturaInnovacion->actividades_muestreo,
            $culturaInnovacion->objetivo_muestreo,
            $culturaInnovacion->recoleccion_especimenes == 1 ? 'Si' : 'No',
            $culturaInnovacion->impacto_municipios,
            $culturaInnovacion->impacto_centro_formacion,
            $culturaInnovacion->objetivo_general,
            $culturaInnovacion->problema_central,
            $culturaInnovacion->justificacion_problema,
            $culturaInnovacion->relacionado_plan_tecnologico == 1 ? 'Si' : ($culturaInnovacion->relacionado_plan_tecnologico == 2 ? 'No' : 'No aplica'),
            $culturaInnovacion->relacionado_agendas_competitividad == 1 ? 'Si' : ($culturaInnovacion->relacionado_agendas_competitividad == 2 ? 'No' : 'No aplica'),
            $culturaInnovacion->relacionado_mesas_sectoriales == 1 ? 'Si' : ($culturaInnovacion->relacionado_mesas_sectoriales == 2 ? 'No' : 'No aplica'),
            $culturaInnovacion->relacionado_tecnoacademia == 1 ? 'Si' : ($culturaInnovacion->relacionado_tecnoacademia == 2 ? 'No' : 'No aplica'),
            $culturaInnovacion->bibliografia,
            $culturaInnovacion->numero_aprendices
        ];
    }

    public function headings(): array
    {
        return [

            'Convocatoria',
            'Regional',
            'Código del centro',
            'Centro de formación',
            'Código del proyecto',
            'Título',
            'Fecha de inicio',
            'Fecha de finalización',
            'Grupo de investigación',
            'Línea de investigación',
            'Área de conocimiento',
            'Temática estratégica',
            'Actividad económica',
            'Video',
            'Justificación de industria 4.0',
            'Justificación de economía naranja',
            'Justificación de política de discapacidad',
            'Resumen',
            'Antecedentes',
            'Marco conceptual',
            'Metodología',
            'Propuesta de sostenibilidad',
            'Muestreo',
            'Actividades del muestreo',
            'Objetivo del muestreo',
            'Recolección de especímentes',
            'Impacto en municipios',
            'Impacto en el centro de formación',
            'Objetivo general',
            'Problema central',
            'Justificación del problema',
            'Relacionado con el plan tecnológico',
            'Relacionado con agendas de competitividad',
            'Relacionado con mesas sectoriales',
            'Relacionado con la TecnoAcademia',
            'Bibliografía',
            'Número de aprendices',
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
        return 'Generalidades';
    }

    public function properties(): array
    {
        return [
            'title' => 'Cultura de Innovacion',
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
