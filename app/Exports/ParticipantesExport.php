<?php

namespace App\Exports;

use App\Models\Convocatorias;
use Maatwebsite\Excel\Concerns\FromCollection;

class ParticipantesExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Convocatorias::all();
    }
}
