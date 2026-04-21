<?php
declare(strict_types=1);

namespace App\Application\Modelos;

use \Illuminate\Database\Eloquent\Model;

class RecuperacionContraseña extends Model {

    protected $table = 'pass_recovery';
    protected $primaryKey = 'id';
    protected $fillable = ['correo','token'];

    
    public $timestamps = false;

  
}