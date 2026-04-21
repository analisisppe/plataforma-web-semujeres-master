<?php

declare(strict_types=1);

namespace App\Application\Modelos\PED;

use \Illuminate\Database\Eloquent\Model;

class ObjetivoPED extends Model {

    protected $table = 'objetivo_ped';

    protected $fillable = [
        'id',
        'objetivo',
        'fk_politica'

                        ];


    public $timestamps = false;

    public function estrategiaPED()
    {
        return $this->hasMany(EstrategiaPED::class,'fk_objetivo');
    }
}