<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresupuestoSennova extends Model
{
    use HasFactory;
        
    /**
     * table
     *
     * @var string
     */
    protected $table = 'presupuesto_sennova';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'primer_grupo_presupuestal_id',
        'segundo_grupo_presupuestal_id',
        'tercer_grupo_presupuestal_id',
        'uso_presupuestal_id',
        'linea_programatica_id',
        'requiere_estudio_mercado',
        'requiere_lote_estudio_mercado',
        'sumar_al_presupuesto',
        'valor_maximo',
        'mensaje'
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
     * Relationship with PrimerGrupoPresupuestal
     *
     * @return void
     */
    public function primerGrupoPresupuestal()
    {
        return $this->belongsTo(PrimerGrupoPresupuestal::class);
    }

    /**
     * Relationship with SegundoGrupoPresupuestal
     *
     * @return void
     */
    public function segundoGrupoPresupuestal()
    {
        return $this->belongsTo(SegundoGrupoPresupuestal::class);
    }

    /**
     * Relationship with TercerGrupoPresupuestal
     *
     * @return void
     */
    public function tercerGrupoPresupuestal()
    {
        return $this->belongsTo(TercerGrupoPresupuestal::class);
    }

    /**
     * Relationship with UsoPresupuestal
     *
     * @return void
     */
    public function usoPresupuestal()
    {
        return $this->belongsTo(UsoPresupuestal::class);
    }

    /**
     * Relationship with LineaProgramatica
     *
     * @return void
     */
    public function lineaProgramatica()
    {
        return $this->belongsTo(LineaProgramatica::class);
    }

    /**
     * Relationship with ConvocatoriaPresupuesto
     *
     * @return void
     */
    public function convocatoriaPresupuesto()
    {
        return $this->hasMany(ConvocatoriaPresupuesto::class);
    }

    /**
     * Filtrar registros
     *
     * @param  mixed $query
     * @param  mixed $filters
     * @return void
     */
    public function scopeFilterPresupuestoSennova($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('mensaje', 'ilike', '%'.$search.'%');
        });
    }
}
