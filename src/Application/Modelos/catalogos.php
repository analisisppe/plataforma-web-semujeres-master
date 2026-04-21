<?php
declare(strict_types=1);

namespace App\Application\Modelos;

use \Illuminate\Database\Eloquent\Model;

class Catalogos extends Model {

    protected $table = 'catalogos';

    protected $fillable = [ 'nombre_catalogo',
                            'catalogo'
                        ];


    public $timestamps = false;

    
}