<?php

declare(strict_types=1);

namespace App\Application\Modelos\PMP;

use \Illuminate\Database\Eloquent\Model;

class Pmp extends Model {

    protected $table = 'pmp';
    protected $primaryKey = 'id_pmp';
    protected $fillable = [
        'id_pmp',
        'tema'

                        ];


    public $timestamps = false;

    public function Objetivopmp()
    {
        return $this->hasMany(ObjetivoPMP::class,'fk_pmp');
    }
}