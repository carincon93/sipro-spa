<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $appends = ['can', 'user_name'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'document_type',
        'document_number',
        'cellphone_number',
        'is_enabled',
        'participation_type',
        'academic_centre_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Relationship with Project (participants)
     *
     * @return void
     */
    public function projects() {
        return $this->belongsToMany(Project::class, 'project_participants', 'user_id', 'project_id')
            ->withPivot([
                'is_author',
                'qty_months',
                'qty_hours'
            ]);
    }

    /**
     * Relationship with AcademicCentre
     *
     * @return void
     */
    public function academicCentre()
    {
        return $this->belongsTo(AcademicCentre::class);
    }

    /**
     * Relationship with AcademicCentre
     *
     * @return void
     */
    public function academicCentreDeputyDirector()
    {
        return $this->hasOne(AcademicCentre::class, 'deputy_director_id');
    }

    /**
     * Relationship with Regional
     *
     * @return void
     */
    public function regionalDirector()
    {
        return $this->hasOne(Regional::class, 'regional_director_id');
    }

    /**
     * Filtrar registros
     *
     * @param  mixed $query
     * @param  mixed $filters
     * @return void
     */
    public function scopeFilterUser($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('name', 'ilike', '%'.$search.'%');
        });
    }

    /**
     * makePassword
     *
     * @param  mixed $documentNumber
     * @return void
     */
    public static function makePassword($documentNumber)
    {
        return bcrypt("Sena$documentNumber*");
    }

    /**
     * getPermissionsAttribute
     *
     * @return void
     */
    public function getCanAttribute()
    {
        return $this->getAllPermissions()->map(function($t) {
            return ['name' => $t['name']];
        })->pluck('name');
    }

    /**
     * getNameAttribute
     *
     * @return void
     */
    public function getNameAttribute($value)
    {
        return ucwords($value);
    }

    /**
     * getUserNameAttribute
     *
     * @return void
     */
    public function getUserNameAttribute()
    {
        return ucwords($this->name);
    }
}
