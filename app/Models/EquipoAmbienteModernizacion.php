<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipoAmbienteModernizacion extends Model
{
    use HasFactory;

    /**
     * table
     *
     * @var string
     */
    protected $table = 'equipos_ambiente_modernizacion';

    /**
     * appends
     *
     * @var array
     */
    public $appends = ['equipo_en_funcionamiento_text'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ambiente_modernizacion_id',
        'nombre_equipo',
        'numero_inventario_equipo',
        'descripcion_tecnica_equipo',
        'estado_equipo',
        'equipo_en_funcionamiento',
        'observaciones_generales',
        'marca',
        'horas_promedio_uso',
        'frecuencia_mantenimiento'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        //
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        //
    ];

    /**
     * Relationship with AmbienteModernizacion
     *
     * @return object
     */
    public function ambienteModernizacion()
    {
        return $this->belongsTo(AmbienteModernizacion::class);
    }

    /**
     * Filtrar registros
     *
     * @param  mixed $query
     * @param  mixed $filters
     * @return void
     */
    public function scopeFilterEquipoAmbienteModernizacion($query, array $filters)
    {
        // En el where reemplazar 'Nombre columna' por el nombre de la columna a filtrar
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('descripcion_tecnica_equipo', 'ilike', '%' . $search . '%');
        });
    }

    /**
     * getEquipoEnFuncionamientoAttribute
     *
     * @param  mixed $value
     * @return void
     */
    public function getEquipoEnFuncionamientoTextAttribute()
    {
        switch ($this->equipo_en_funcionamiento) {
            case 1:
                $this->equipo_en_funcionamiento = 'Si';
                break;
            case 2:
                $this->equipo_en_funcionamiento = 'No';
                break;
            default:
                break;
        }
        return $this->equipo_en_funcionamiento;
    }
}
