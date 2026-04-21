<?php

declare(strict_types=1);

namespace App\Application\Modelos\INDICADORES;

use \Illuminate\Database\Eloquent\Model;

class Valor_Indicadores extends Model {

    protected $table = 'valor_indicadores';
    protected $primaryKey = 'id_valor_indicadores';
    protected $fillable = [
        'mes',
        'año',
        'valora',
        'valorb',
        'valorc',
        'fk_indicadores'
                        ];


    public $timestamps = false;
    

}