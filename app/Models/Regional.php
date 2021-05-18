<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Regional extends Model
{
    use HasFactory;

    /**
     * table
     *
     * @var string
     */
    protected $table = 'regionales';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre',
        'codigo',
        'region_id',
        'director_regional_id'
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
     * Relationship with Region
     *
     * @return object
     */
    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    /**
     * Relationship with User
     *
     * @return object
     */
    public function directorRegional()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship with AcademicCentre
     *
     * @return object
     */
    public function academicCentres()
    {
        return $this->hasMany(AcademicCentre::class);
    }

    /**
     * Filtrar registros
     *
     * @param  mixed $query
     * @param  mixed $filters
     * @return void
     */
    public function scopeFilterRegional($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('nombre', 'ilike', '%' . $search . '%');
        });
    }
}
