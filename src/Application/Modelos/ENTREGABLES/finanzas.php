<?php

declare(strict_types=1);

namespace App\Application\Modelos\ENTREGABLES;

use \Illuminate\Database\Eloquent\Model;

class Finanzas extends Model {

    protected $table = 'finanzas';

    protected $primaryKey = 'id';
    protected $fillable = [
        'fuente',
        'monto',
        'porcentaje_ubp',
        'fk_id_entregable'
                        ];


    public $timestamps = false;



}