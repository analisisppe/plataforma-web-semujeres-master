<?php

declare(strict_types=1);

namespace App\Application\Modelos\PED;

use \Illuminate\Database\Eloquent\Model;

class Politica extends Model {

    protected $table = 'politica_publica';

    protected $fillable = [
        'id',
        'politica_publica',
        'fk_eje'

                        ];


    public $timestamps = false;

    public function objetivoPED()
    {
        return $this->hasMany(ObjetivoPED::class,'fk_politica');
    }
}