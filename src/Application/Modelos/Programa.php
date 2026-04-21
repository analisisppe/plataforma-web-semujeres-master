<?php
declare(strict_types=1);

namespace App\Application\Modelos;

use App\Application\Modelos\PED\Alineacionped;
use \Illuminate\Database\Eloquent\Model;

class Programa extends Model {

    protected $table = 'programa';

    protected $primaryKey = 'id_programa';

    protected $fillable = ['nombre_programa', 
                            'año',
                            'objetivo',
                            'descripción',
                            'nombre_responsable', 
                            'cargo_responsable',
                            'correo_responsable',
                            'tel_responsable',
                            'brecha_genero',
                            'ejeped',
                            'politicaped',
                            'objetivoped',
                            'estrategiaped',
                            'lineaped',
                            'fk_user',
                            'rol_usuario'
                            ];


    public $timestamps = false;

    public function entregables()
    {
        return $this->hasMany(Entregable::class,'fk_id_programa');
    }

    public function alineacionped(){
        return $this->hasMany(Alineacionped::class,'fk_id_programa');
    }

    public static function boot() {
        parent::boot();

        self::deleting(function($programa) { // before delete() method call this
            $programa->entregables()->each(function($en) {
                $en->delete(); // <-- direct deletion
             });

             $programa->alineacionped()->each(function($aped){
                $aped->delete();// <-- direct deletion
            });

        });
    
    }
}