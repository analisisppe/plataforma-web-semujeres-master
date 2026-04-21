<?php

declare(strict_types=1);

namespace App\Application\Modelos\PED;

use \Illuminate\Database\Eloquent\Model;

class EstrategiaPED extends Model {

    protected $table = 'estrategia_ped';

    protected $fillable = [
        'id',
        'estrategia',
        'fk_objetivo'

                        ];


    public $timestamps = false;
    public function lineaAccionPED()
    {
        return $this->hasMany(LineaAccionPED::class,'fk_estrategia');
    }
   
}