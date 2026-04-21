<?php

declare(strict_types=1);

namespace App\Application\Modelos\PMP;

use App\Application\Modelos\PED\EstrategiaPED;
use \Illuminate\Database\Eloquent\Model;

class ObjetivoPMP extends Model {

    protected $table = 'objetivo_estrategias';
    protected $primaryKey = 'id_obj';
    protected $fillable = [
        'id_obj',
        'obj_estrategia',
        'fk_pmp'

                        ];


    public $timestamps = false;

    public function Estrategiapmp()
    {
        return $this->hasMany(EstrategiaPMP::class,'fk_obj');
    }
}