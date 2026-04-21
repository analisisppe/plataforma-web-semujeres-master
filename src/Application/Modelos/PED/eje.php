<?php

declare(strict_types=1);

namespace App\Application\Modelos\PED;

use \Illuminate\Database\Eloquent\Model;

class Eje extends Model {

    protected $table = 'eje';

    protected $fillable = [
        'id',
        'eje'

                        ];


    public $timestamps = false;

    public function politicapublica()
    {
        return $this->hasMany(PoliticaPublica::class,'fk_eje');
    }
}