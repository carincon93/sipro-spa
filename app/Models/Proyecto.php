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
    protected $appends = ['codigo', 'diff_meses', 'total_proyecto_presupuesto', 'total_maquinaria_industrial', 'total_servicios_especiales_construccion', 'total_viaticos', 'total_mantenimiento_maquinaria'];

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

    /**
     * Relationship with ProyectoAnexo
     *
     * @return void
     */
    public function proyectoAnexo()
    {
        return $this->hasMany(ProyectoAnexo::class);
    }

    /**
     * Relationship with AnalisisRiesgo
     *
     * @return void
     */
    public function analisisRiesgo()
    {
        return $this->hasMany(AnalisisRiesgo::class);
    }
    
    /**
     * Relationship with ProyectoPresupuesto
     *
     * @return void
     */
    public function proyectoPresupuesto()
    {
        return $this->hasMany(ProyectoPresupuesto::class);
    }


    /**
     * Relationship with Proyecto (participantes)
     *
     * @return void
     */
    public function participantes()
    {
        return $this->belongsToMany(User::class, 'proyecto_participantes', 'proyecto_id', 'user_id')
            ->withPivot([
                'user_id',
                'es_autor',
                'numero_meses',
                'numero_horas'
            ]);
    }


        /**
     * Get codigo e.g. SGPS-8000-2021
     *
     * @return void
     */
    public function getCodigoAttribute()
    {
        return 'SGPS-' . ($this->id + 8000) .'-' . date('Y', strtotime($this->idi->fecha_finalizacion));
    }


    public function getDiffMesesAttribute()
    {
        $fecha_finalizacion = Carbon::parse($this->idi->fecha_finalizacion, 'UTC')->floorMonth();
        $fecha_inicio       = Carbon::parse($this->idi->fecha_inicio, 'UTC')->floorMonth();
        return $fecha_inicio->diffInMonths($fecha_finalizacion);
    }
    

    public function getTotalProyectoPresupuestoAttribute()
    {
        $total = 0;

        foreach($this->proyectoPresupuesto as $proyectoPresupuesto) {
            if ($proyectoPresupuesto->convocatoriaPresupuesto->presupuestoSennova->sumar_al_presupuesto) {
                $total += $proyectoPresupuesto->getPromedioAttribute();
            }
        }

        return $total;
    }

    // Validación de la línea 66
    // Porcentaje total del rubro 'Maquinaria Industrial'
    public function getTotalMaquinariaIndustrialAttribute()
    {
        $total = 0;
        $segundoGrupoPresupuestalId = null;

        foreach($this->proyectoPresupuesto as $proyectoPresupuesto) {

            if ($proyectoPresupuesto->convocatoriaPresupuesto->presupuestoSennova->sumar_al_presupuesto && $proyectoPresupuesto->convocatoriaPresupuesto->presupuestoSennova->segundoGrupoPresupuestal->codigo == '2040115') {
                $total += $proyectoPresupuesto->getPromedioAttribute();
            }
        }

        return $total;
    }

    public function getPorcentajeMaquinariaIndustrialAttribute()
    {
        $total = 0;

        return $this->getTotalMaquinariaIndustrialAttribute() * 0.05;
    }

    // Validación de la línea 66
    // Total del rubro 'Servicios especiales de construcción'
    public function getTotalServiciosEspecialesConstruccionAttribute()
    {
        $total = 0;

        foreach($this->proyectoPresupuesto as $proyectoPresupuesto) {

            if ($proyectoPresupuesto->convocatoriaPresupuesto->presupuestoSennova->sumar_al_presupuesto && $proyectoPresupuesto->convocatoriaPresupuesto->presupuestoSennova->usoPresupuestal->codigo == '2020200500405') {
                $total += $proyectoPresupuesto->getPromedioAttribute();
            }
        }

        return $total;
    }

    // Validación de la línea 66
    // Total del rubro 'Viáticos'
    public function getTotalViaticosAttribute()
    {
        $total = 0;

        foreach($this->proyectoPresupuesto as $proyectoPresupuesto) {
            if ($proyectoPresupuesto->convocatoriaPresupuesto->presupuestoSennova->sumar_al_presupuesto) {
                if ($proyectoPresupuesto->convocatoriaPresupuesto->presupuestoSennova->usoPresupuestal->codigo == '2020200600301' || $proyectoPresupuesto->convocatoriaPresupuesto->presupuestoSennova->usoPresupuestal->codigo == '2020200600303' || $proyectoPresupuesto->convocatoriaPresupuesto->presupuestoSennova->usoPresupuestal->codigo == '20202006004') {
                    $total += $proyectoPresupuesto->getPromedioAttribute();
                }
            }
        }

        return $total;
    }

    // Validación de la línea 66
    // Total del rubro 'MANTENIMIENTO DE MAQUINARIA, EQUIPO, TRANSPORTE Y SOFWARE'
    public function getTotalMantenimientoMaquinariaAttribute()
    {
        $total = 0;

        foreach($this->proyectoPresupuesto as $proyectoPresupuesto) {
            if ($proyectoPresupuesto->convocatoriaPresupuesto->presupuestoSennova->sumar_al_presupuesto) {
                if ($proyectoPresupuesto->convocatoriaPresupuesto->presupuestoSennova->usoPresupuestal->codigo == '20202008007013' || $proyectoPresupuesto->convocatoriaPresupuesto->presupuestoSennova->usoPresupuestal->codigo == '20202008007012' || $proyectoPresupuesto->convocatoriaPresupuesto->presupuestoSennova->usoPresupuestal->codigo == '20202008007014' || $proyectoPresupuesto->convocatoriaPresupuesto->presupuestoSennova->usoPresupuestal->codigo == '20202008007015' || $proyectoPresupuesto->convocatoriaPresupuesto->presupuestoSennova->usoPresupuestal->codigo == '20202008007011') {
                    $total += $proyectoPresupuesto->getPromedioAttribute();
                }
            }
        }

        return $total;
    }

    // Validación de la línea 66
    // Total del monitorías
    public function getTotalMonitorias()
    {
        $total = 0;

        foreach($this->proyectoPresupuesto as $proyectoPresupuesto) {
            $total = $proyectoPresupuesto->convocatoriaPresupuesto->presupuestoSennova->usoPresupuestal()->where('codigo', '20202008005099')->count();
        }

        return $total;
    }
}
