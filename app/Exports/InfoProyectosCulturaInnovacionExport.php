<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use App\Models\Convocatoria;

class InfoProyectosCulturaInnovacionExport implements WithMultipleSheets, WithTitle
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

        $sheets[] = new GeneralidadesCulturaInnovacionExport($this->convocatoria, 9);
        $sheets[] = new SemillerosnvestigacionExport($this->convocatoria, 9);
        $sheets[] = new AnalisisRiesgosExport($this->convocatoria, 9);
        $sheets[] = new AnexosExport($this->convocatoria, 9);
        $sheets[] = new EfectosDirectosExport($this->convocatoria, 9);
        $sheets[] = new EfectosIndirectosExport($this->convocatoria, 9);
        $sheets[] = new CausasDirectasExport($this->convocatoria, 9);
        $sheets[] = new CausasIndirectasExport($this->convocatoria, 9);
        $sheets[] = new ImpactosExport($this->convocatoria, 9);
        $sheets[] = new ResultadosExport($this->convocatoria, 9);
        $sheets[] = new ObjetivosEspecificosExport($this->convocatoria, 9);
        $sheets[] = new ActividadesExport($this->convocatoria, 9);
        $sheets[] = new MunicipiosImpactadosExport($this->convocatoria, 9);

        return $sheets;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Cultura de innovación';
    }
}
