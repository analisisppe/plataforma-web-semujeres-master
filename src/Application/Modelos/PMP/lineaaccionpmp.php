<?php

declare(strict_types=1);

namespace App\Application\Modelos\PMP;

use \Illuminate\Database\Eloquent\Model;

class LineaAccionPMP extends Model {

    protected $table = 'linea_accion_pmp';
    protected $primaryKey = 'id_linea_pmp';
    protected $fillable = [
        'id_linea_pmp',
        'linea_pmp',
        'fk_estrategia_pmp'

                        ];


    public $timestamps = false;

    
}