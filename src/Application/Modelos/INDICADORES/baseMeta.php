<?php

declare(strict_types=1);

namespace App\Application\Modelos\INDICADORES;

use \Illuminate\Database\Eloquent\Model;

class BaseMeta extends Model {

    protected $table = 'base_meta_indicador';
    protected $primaryKey = 'id';
    protected $fillable = [
        'año',
        'linea_base',
        'meta',
        'fk_indicador'
                        ];


    public $timestamps = false;


}