<?php

declare(strict_types=1);

namespace App\Application\Modelos\INDICADORES;

use \Illuminate\Database\Eloquent\Model;

class Variables extends Model {

    protected $table = 'var_indicador';
    protected $primaryKey = 'id_variable';
    protected $fillable = [
        'id_variable',
        'variable',
        'nombre',
        'fk_indicador'
                        ];


    public $timestamps = false;


}