<?php
declare(strict_types=1);

namespace App\Application\Modelos;

use \Illuminate\Database\Eloquent\Model;

class Ficha extends Model {

    protected $table = 'ficha';
    protected $primaryKey = 'id';

    protected $fillable = ['numero_indicador',
                            'año',
                            'ficha'
                        ];


   public $timestamps = false;

    
}