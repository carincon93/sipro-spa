<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use App\Models\Convocatoria;

class InfoProyectosTaExport implements WithMultipleSheets, WithTitle
{
    use Exportable;

    protected $convocatoria;

    public function __construct(Convocatoria $convocatoria)
    {
        $this->convocatoria = $convocatoria;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];

        $sheets[] = new GeneralidadesTaExport($this->convocatoria);
        $sheets[] = new GruposInvestigacionTaExport($this->convocatoria);
        $sheets[] = new SemillerosnvestigacionTaExport($this->convocatoria);
        $sheets[] = new LineasInvestigacionTaExport($this->convocatoria);
        $sheets[] = new DisciplinasSubareaConocimientoTaExport($this->convocatoria);
        $sheets[] = new RedesConocimientoTaExport($this->convocatoria);
        $sheets[] = new TematicasEstrategicasTaExport($this->convocatoria);
        $sheets[] = new ActividadesEconomicasTaExport($this->convocatoria);
        $sheets[] = new ProgramasFormacionTaExport($this->convocatoria);
        $sheets[] = new EdtTaExport($this->convocatoria);
        $sheets[] = new ProductosTaExport($this->convocatoria);
        $sheets[] = new AnalisisRiesgosTaExport($this->convocatoria);
        $sheets[] = new EntidadesAliadasTaExport($this->convocatoria);
        $sheets[] = new AnexosTaExport($this->convocatoria);
        $sheets[] = new EfectosDirectosTaExport($this->convocatoria);
        $sheets[] = new EfectosIndirectosTaExport($this->convocatoria);
        $sheets[] = new CausassDirectasTaExport($this->convocatoria);
        $sheets[] = new CausasIndirectasTaExport($this->convocatoria);
        $sheets[] = new ImpactosTaExport($this->convocatoria);
        $sheets[] = new ResultadosTaExport($this->convocatoria);
        $sheets[] = new ObjetivosEspecificosTaExport($this->convocatoria);
        $sheets[] = new ActividadesTaExport($this->convocatoria);
        $sheets[] = new DisenoCurricularTaExport($this->convocatoria);
        $sheets[] = new MunicipiosImpactadosTaExport($this->convocatoria);
        $sheets[] = new MunicipiosAImpactarTaExport($this->convocatoria);

        return $sheets;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Proyectos TA';
    }
}
