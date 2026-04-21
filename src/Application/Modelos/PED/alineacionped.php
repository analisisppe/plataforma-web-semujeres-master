<?php

declare(strict_types=1);

namespace App\Application\Modelos\PED;

use \Illuminate\Database\Eloquent\Model;

class Alineacionped extends Model {

    protected $table = 'alineacion_ped';

    protected $primaryKey = 'id';
    protected $fillable = [
        'eje',
        'politica',
        'objetivo',
        'estrategia',
        'linea',
        'fk_id_programa'
                        ];


    public $timestamps = false;



}