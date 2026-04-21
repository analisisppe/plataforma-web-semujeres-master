<?php
declare(strict_types=1);

namespace App\Application\Modelos;

use \Illuminate\Database\Eloquent\Model;

class Avg extends Model {

    protected $table = 'avg';

    protected $fillable = ['nombre_conclusion',
                            'descripción'
                        ];


    public $timestamps = false;

    
}