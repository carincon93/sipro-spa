<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LineaProgramatica extends Model
{
    use HasFactory;

    /**
     * table
     *
     * @var string
     */
    protected $table = 'lineas_programaticas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre',
        'descripcion',
        'codigo',
        'categoria_proyecto'
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
     * Relationship with Proyecto
     *
     * @return object
     */
    public function proyectos()
    {
        return $this->belongsTo(Proyecto::class);
    }

    /**
     * Relationship with PresupuestoSennova
     *
     * @return object
     */
    public function presupuestoSennova()
    {
        return $this->hasMany(PresupuestoSennova::class);
    }

    /**
     * Relationship with ConvocatoriaRolSennova
     *
     * @return object
     */
    public function convocatoriaRolSennova()
    {
        return $this->hasMany(ConvocatoriaRolSennova::class);
    }

    /**
     * Relationship with Anexo
     *
     * @return object
     */
    public function anexos()
    {
        return $this->belongsToMany(Anexo::class, 'anexos_linea_programatica', 'linea_programatica_id', 'anexo_id');
    }

    /**
     * Filtrar registros
     *
     * @param  mixed $query
     * @param  mixed $filters
     * @return void
     */
    public function scopeFilterLineaProgramatica($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $search = str_replace(' ', '%%', $search);
            $search = str_replace('"', "", $search);
            $search = str_replace("'", "", $search);
            $query->whereRaw("unaccent(nombre) ilike unaccent('%" . $search . "%')");
            $query->orWhere('codigo', 'ilike', '%' . $search . '%');
            $query->orWhere('categoria_proyecto', 'ilike', '%' . $search . '%');
        });
    }

    /**
     * getCategoriaProyectoAttribute
     *
     * @param  mixed $value
     * @return void
     */
    public function getCategoriaProyectoAttribute($value)
    {
        switch ($value) {
            case 1:
                $value = 'Tecnoacademia-Tecnoparque';
                break;
            case 2:
                $value = 'I+D+i';
                break;
            case 3:
                $value = 'Servicios tecnológicos';
                break;
            case 4:
                $value = 'Otro';
                break;
            default:
                break;
        }
        return $value;
    }
}
