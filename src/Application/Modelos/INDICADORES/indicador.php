<?php

declare(strict_types=1);

namespace App\Application\Modelos\INDICADORES;

use \Illuminate\Database\Eloquent\Model;

class Indicador extends Model {

    protected $table = 'indicador';
    protected $primaryKey = 'id_indicador';
    protected $fillable = [
        'id_indicador',
        'numero',
        'definicion',
        'fk_pp'
                        ];


    public $timestamps = false;

    public function variables()
    {
        return $this->hasMany(Variables::class,'fk_indicador');
    }

    public function baseMeta(){
        return $this->hasOne(BaseMeta::class, 'fk_indicador');
    }

}