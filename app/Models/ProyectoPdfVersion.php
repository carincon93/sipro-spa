<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ProyectoPdfVersion extends Model
{
    use HasFactory;

    /**
     * table
     *
     * @var string
     */
    protected $table = 'proyecto_pdf_versiones';

    /**
     * appends
     *
     * @var array
     */
    protected $appends = ['fecha_generacion_pdf'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'version'
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
     * @return void
     */
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }

    /**
     * Filtrar registros
     *
     * @param  mixed $query
     * @param  mixed $filters
     * @return void
     */
    public function scopeFilterProyectoPdfVersion($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('version', 'ilike', '%' . $search . '%');
        });
    }

    /**
     * getFechaGeneracionPdfAttribute
     *
     * @return void
     */
    public function getFechaGeneracionPdfAttribute()
    {
        return Carbon::parse($this->created_at, 'UTC')->locale('es')->isoFormat('DD [de] MMMM [de] YYYY');
    }
}
