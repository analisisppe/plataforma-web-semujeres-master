<?php
declare(strict_types=1);

namespace App\Application\Modelos;

use \Illuminate\Database\Eloquent\Model;

class Dependencias extends Model {

    protected $table = 'dependencias';

    protected $primaryKey = 'id_dep';

    protected $fillable = ['nombre_dep'];


    public $timestamps = false;

 
}