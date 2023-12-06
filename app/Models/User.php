<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Permission;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $primaryKey = "pk_utilizador";

    protected $table = "mca_tb_utilizador";
    
    public $timestamps = false;

    protected $fillable = [
        'codigo_importado',
        'nome',
        'userName',
        'password',
        'obs',
        'ref_pessoa'
    ];
    
    public function tipo_grupo()
    {
        return $this->hasOne(GrupoUtilizador::class, 'fk_utilizador', 'pk_utilizador');
    }
    
    public function pessoa()
    {
        return $this->hasOne(Pessoa::class, 'pk_pessoa', 'json_extract(mca_tb_utilizador.ref_pessoa, "$.pk")');
    }

    // /**
    //  * The attributes that are mass assignable.
    //  *
    //  * @var array<int, string>
    //  */
    // protected $fillable = [
    //     'name',
    //     'email',
    //     'telefone',
    //     'tipo_de_documento',
    //     'numero_documento',
    //     'canal',
    //     'username',
    //     'grauacademico',
    //     'faculdade',
    //     'estado',
    //     'foto',
    //     'status',
    //     'ano_lectivo_id',
    //     'password',
    // ];

    // /**
    //  * The attributes that should be hidden for serialization.
    //  *
    //  * @var array<int, string>
    //  */
    // protected $hidden = [
    //     'password',
    //     'remember_token',
    // ];

    // /**
    //  * The attributes that should be cast.
    //  *
    //  * @var array<string, string>
    //  */
    // protected $casts = [
    //     'email_verified_at' => 'datetime',
    // ];
    
     /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['all_permissions', 'can'];

    /**
     * Get all user permissions.
     *
     * @return bool
     */
    public function getAllPermissionsAttribute()
    {
        return $this->getAllPermissions();
    }

    /**
     * Get all user permissions in a flat array.
     *
     * @return array
     */
    public function getCanAttribute()
    {
        $permissions = [];
        foreach (Permission::all() as $permission) {

            if (Auth::user())
                if (Auth::user()->can($permission->name)) {
                    $permissions[$permission->name] = true;
                } else {
                    $permissions[$permission->name] = false;
                }
        }
        return $permissions;
    }
}
