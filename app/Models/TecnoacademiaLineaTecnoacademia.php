<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TecnoacademiaLineaTecnoacademia extends Model
{
    use HasFactory;

    /**
     * table
     *
     * @var string
     */
    protected $table = 'tecnoacademia_linea_tecnoacademia';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tecnoacademia_id',
        'linea_tecnoacademia_id'
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
     * Relationship with Tecnoacademia
     *
     * @return void
     */
    public function tecnoacademia()
    {
        return $this->belongsTo(Tecnoacademia::class);
    }

    /**
     * Relationship with LineaTecnoacademia
     *
     * @return void
     */
    public function lineaTecnoacademia()
    {
        return $this->belongsTo(LineaTecnoacademia::class);
    }

    /**
     * Relationship with Idi
     *
     * @return void
     */
    public function idi()
    {
        return $this->hasMany(Idi::class);
    }

    /**
     * Relationship with CulturaInnovacion
     *
     * @return void
     */
    public function culturaInnovacion()
    {
        return $this->hasMany(CulturaInnovacion::class);
    }

    /**
     * Relationship with Ta
     *
     * @return void
     */
    public function ta()
    {
        return $this->hasMany(Ta::class);
    }
}
