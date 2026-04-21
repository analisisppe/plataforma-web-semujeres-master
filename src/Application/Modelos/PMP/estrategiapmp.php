<?php

declare(strict_types=1);

namespace App\Application\Modelos\PMP;

use \Illuminate\Database\Eloquent\Model;

class EstrategiaPMP extends Model {

    protected $table = 'estrategia_pmp';
    protected $primaryKey = 'id_estrategia';
    protected $fillable = [
        'id_estrategia',
        'estrategia_pmp',
        'fk_obj'

                        ];


    public $timestamps = false;
    public function LineaAccionpmp()
    {
        return $this->hasMany(LineaAccionPMP::class,'fk_estrategia_pmp');
    }
    
}