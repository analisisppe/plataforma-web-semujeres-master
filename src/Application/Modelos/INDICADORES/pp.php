<?php

declare(strict_types=1);

namespace App\Application\Modelos\INDICADORES;

use \Illuminate\Database\Eloquent\Model;

class Pp extends Model {

    protected $table = 'programa_presupuestario';
    protected $primaryKey = 'id_pp';
    protected $fillable = [
        'id_pp',
        'pp'
                        ];


    public $timestamps = false;
    public function Indicador()
    {
        return $this->hasMany(Indicador::class,'fk_pp');
    }

}