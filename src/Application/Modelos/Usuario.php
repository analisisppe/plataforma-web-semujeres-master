<?php
declare(strict_types=1);

namespace App\Application\Modelos;

use \Illuminate\Database\Eloquent\Model;

class Usuario extends Model {

    protected $table = 'usuario';
    protected $primaryKey = 'usuario_id';
    protected $fillable = ['correo','clave_acceso','nombre_usuario','apellido_usuario','dependencia','unidad_admin','rol','foto_perfil'];

    //protected $guarded = ['id', 'password'];

    public $timestamps = false;

    public function programa()
    {
        return $this->hasMany(Programa::class,'fk_user');
    }
    
    
    public function indicadores()
    {
        return $this->hasMany(Indicadores::class,'fk_user');
    }
}