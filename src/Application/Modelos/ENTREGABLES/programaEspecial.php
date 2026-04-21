<?php

declare(strict_types=1);

namespace App\Application\Modelos\ENTREGABLES;

use \Illuminate\Database\Eloquent\Model;

class ProgramaEspecial extends Model {

    protected $table = 'p_especial';

    protected $primaryKey = 'id';
    protected $fillable = [
        'programa',
        'objetivo',
        'estrategia',
        'linea_accion',
        'fk_id_entregable'
                        ];


    public $timestamps = false;



}