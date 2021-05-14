<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RDIOutput extends Model
{
    use HasFactory;

    /**
     * table
     *
     * @var string
     */
    protected $table = 'rdi_outputs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'output_id',
        'minciencias_subtypology_id'
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
     * Relationship with MincienciasSubtypology
     *
     * @return void
     */
    public function mincienciasSubtypology()
    {
        return $this->belongsTo(MincienciasSubtypology::class);
    }

    /**
     * Relationship with Output
     *
     * @return void
     */
    public function output()
    {
        return $this->belongsTo(Output::class);
    }
}
