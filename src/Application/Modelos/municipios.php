<?php
declare(strict_types=1);

namespace App\Application\Modelos;

use \Illuminate\Database\Eloquent\Model;

class Municipios extends Model {

    protected $table = 'municipios';

    protected $primaryKey = 'id';

    protected $fillable = ['id','nombre'];


    public $timestamps = false;

 
}