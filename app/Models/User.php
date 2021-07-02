<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * appends
     *
     * @var array
     */
    protected $appends = ['can'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre',
        'email',
        'password',
        'tipo_documento',
        'numero_documento',
        'numero_celular',
        'habilitado',
        'tipo_vinculacion',
        'centro_formacion_id',
        'autorizacion_datos'
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
     * Relationship with Proyecto (participants)
     *
     * @return object
     */
    public function proyectos()
    {
        return $this->belongsToMany(Proyecto::class, 'proyecto_participantes', 'user_id', 'proyecto_id')
            ->withPivot([
                'user_id',
                'es_formulador',
                'cantidad_meses',
                'cantidad_horas',
                'rol_sennova'
            ]);
    }

    /**
     * Relationship with CentroFormacion
     *
     * @return object
     */
    public function centroFormacion()
    {
        return $this->belongsTo(CentroFormacion::class);
    }

    /**
     * Relationship with CentroFormacion
     *
     * @return object
     */
    public function dinamizadorCentroFormacion()
    {
        return $this->hasOne(CentroFormacion::class, 'dinamizador_sennova_id');
    }

    /**
     * Relationship with CentroFormacion
     *
     * @return object
     */
    public function subdirectorCentroFormacion()
    {
        return $this->hasOne(CentroFormacion::class, 'subdirector_id');
    }

    /**
     * Relationship with Regional
     *
     * @return object
     */
    public function directorRegional()
    {
        return $this->hasOne(Regional::class, 'director_regional_id');
    }

    /**
     * Relationship with User
     *
     * @return object
     */
    public function lideresLineaProgramatica()
    {
        return $this->belongsToMany(LineaProgramatica::class, 'lider_linea_programatica', 'user_id', 'linea_programatica_id');
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
            $search = str_replace(' ', '%%', $search);
            $search = str_replace('"', "", $search);
            $search = str_replace("'", "", $search);
            $query->whereRaw("unaccent(nombre) ilike unaccent('%" . $search . "%')");
            $query->orWhere('email', 'ilike', '%' . $search . '%');
            $query->orWhere('numero_documento', 'ilike', '%' . $search . '%');
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
        return bcrypt("sena$documentNumber*");
    }

    /**
     * getPermissionsAttribute
     *
     * @return void
     */
    public function getCanAttribute()
    {
        return $this->getAllPermissions()->map(function ($t) {
            return ['id' => $t['id']];
        })->pluck('id');
    }

    /**
     * getUsersByRol
     *
     * @return object
     */
    public static function getUsersByRol()
    {
        $user = Auth::user();
        if ($user->hasRole(1)) {
            $users = User::orderBy('nombre', 'ASC')
                ->filterUser(request()->only('search'))->paginate();
        } else if ($user->hasRole(4) && $user->dinamizadorCentroFormacion()->exists()) {
            $users = User::where('centro_formacion_id', $user->dinamizadorCentroFormacion->id)->orderBy('nombre', 'ASC')
                ->filterUser(request()->only('search'))->paginate();
        }
        return $users;
    }
}
