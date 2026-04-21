<?php
declare(strict_types=1);

namespace App\Application\Modelos;

use \Illuminate\Database\Eloquent\Model;

class Compromiso extends Model {

    protected $table = 'compromisos';

    protected $fillable = ['id',
                            'descripción'
                        ];


    public $timestamps = false;

    
}