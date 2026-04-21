<?php
declare(strict_types=1);

namespace App\Application\Modelos;

use \Illuminate\Database\Eloquent\Model;

class Informe extends Model {

    protected $table = 'informe';
    protected $primaryKey = 'id_informe';

    protected $fillable = ['trimestre',
                            'periodo',
                            'accion',
                            'personas',
                            'municipios',
                            'objetivo',
                            'descripcion',
                            'fk_id_entregable',
                            'informe_finalizado'
                        ];


   public $timestamps = false;

    
}