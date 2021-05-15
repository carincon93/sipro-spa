<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Proyecto extends Model
{
    use HasFactory;

    /**
     * table
     *
     * @var string
     */
    protected $table = 'proyectos';
    
    /**
     * appends
     *
     * @var array
     */
    protected $appends = ['diff_meses'];
    // protected $appends = ['code', 'diff_months', 'total_project_budget', 'total_industrial_machinery', 'total_special_construction_services', 'total_viatics', 'total_machinery_maintenance'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'convocatoria_id',
        'centro_formacion_id',
        'tipo_proyecto_id',
        'esta_finalizado'
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
     * Relationship with Convocatoria
     *
     * @return void
     */
    public function convocatoria()
    {
        return $this->belongsTo(Convocatoria::class);
    }


    /**
     * Relationship with TipoProyecto
     *
     * @return void
     */
    public function tipoProyecto()
    {
        return $this->belongsTo(TipoProyecto::class);
    }

    /**
     * Relationship with CentroFormacion
     *
     * @return void
     */
    public function centroFormacion()
    {
        return $this->belongsTo(CentroFormacion::class);
    }

    /**
     * Relationship with RDI
     *
     * @return void
     */
    public function idi()
    {
        return $this->hasOne(IDi::class, 'id');
    }

    /**
     * Relationship with Municipio
     *
     * @return void
     */
    public function municipios()
    {
        return $this->belongsToMany(Municipio::class, 'proyecto_municipio', 'proyecto_id', 'municipio_id')->orderBy('municipios.nombre', 'ASC');
    }

    /**
     * Relationship with CausaDirecta
     *
     * @return void
     */
    public function causasDirectas()
    {
        return $this->hasMany(CausaDirecta::class)->orderBy('id', 'ASC');
    }

    /**
     * Relationship with EfectoDirecto
     *
     * @return void
     */
    public function efectosDirectos()
    {
        return $this->hasMany(EfectoDirecto::class)->orderBy('id', 'ASC');
    }

    /**
     * Relationship with ProyectoRolSennova
     *
     * @return void
     */
    public function proyectoRolesSennova()
    {
        return $this->hasMany(ProyectoRolSennova::class);
    }


    public function getDiffMesesAttribute()
    {
        $fecha_finalizacion = Carbon::parse($this->idi->fecha_finalizacion, 'UTC')->floorMonth();
        $fecha_inicio       = Carbon::parse($this->idi->fecha_inicio, 'UTC')->floorMonth();
        return $fecha_inicio->diffInMonths($fecha_finalizacion);
    }
}
