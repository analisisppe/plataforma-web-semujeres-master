<?php
declare(strict_types=1);

namespace App\Application\Modelos;

use App\Application\Modelos\ENTREGABLES\Alineacion_ped;
use App\Application\Modelos\ENTREGABLES\Finanzas;
use App\Application\Modelos\ENTREGABLES\OdsEntregable;
use App\Application\Modelos\ENTREGABLES\ProgramaEspecial;
use \Illuminate\Database\Eloquent\Model;

class Entregable extends Model {

    protected $table = 'entregable';
    protected $primaryKey = 'id_entregable';

    protected $fillable = ['nombre_entregable',
                            'periodicidad',
                        'unidad_medida',
                        'meta',
                        'municipalizable',
                        'compromiso',   
                        'ods', 
                        'actividad_sigo',
                        'entregable_sigo',
                        'avg',
                        'monto_total',
                        'porcentaje_ubp_total',
                        'fk_id_programa'
                        ];


    public $timestamps = false;

    public function avances()
    {
        return $this->hasMany(Avance::class,'fk_id_entregable');
    }

    public function informe()
    {
        return $this->hasMany(Informe::class,'fk_id_entregable');
    }

    public function finanzas(){
        return $this->hasMany(Finanzas::class,'fk_id_entregable');
    }

    public function programaespecial(){
        return $this->hasMany(ProgramaEspecial::class,'fk_id_entregable');
    }

  
    
    public static function boot() {
        parent::boot();

        self::deleting(function($entregable) { // before delete() method call this
            $entregable->avances()->each(function($av) {
                $av->delete(); // <-- direct deletion
             });

             $entregable->informe()->each(function($in) {
                $in->delete(); // <-- direct deletion
             });

             $entregable->finanzas()->each(function($fi) {
                $fi->delete(); // <-- direct deletion
             });

             $entregable->programaespecial()->each(function($pe) {
                $pe->delete(); // <-- direct deletion
             });
           
             
        });
    
    }
}