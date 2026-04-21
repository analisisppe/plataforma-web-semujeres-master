<?php

declare(strict_types=1);

namespace App\Application\Modelos\PED;

use \Illuminate\Database\Eloquent\Model;

class LineaAccionPED extends Model {

    protected $table = 'lineas_accion_ped';

    protected $fillable = [
        'id',
        'linea_accion',
        'fk_estrategia'

                        ];


    public $timestamps = false;

   
}