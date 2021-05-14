<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectSennovaRole extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id',
        'call_sennova_role_id',
        'description',
        'qty_months',
        'qty_roles'
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
     * Relationship with Project
     *
     * @return void
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Relationship with CallSennovaRole
     *
     * @return void
     */
    public function callSennovaRole()
    {
        return $this->belongsTo(CallSennovaRole::class);
    }

    /**
     * Filtrar registros
     *
     * @param  mixed $query
     * @param  mixed $filters
     * @return void
     */
    public function scopeFilterProjectSennovaRole($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->join('call_sennova_roles', 'project_sennova_roles.call_sennova_role_id', 'call_sennova_roles.id')
                ->join('sennova_roles', 'call_sennova_roles.sennova_role_id', 'sennova_roles.id')
                ->where('call_sennova_roles.salary', 'ilike', '%'.$search.'%')->orWhere('sennova_roles.name', 'ilike', '%'.$search.'%');
        });
    }
}
