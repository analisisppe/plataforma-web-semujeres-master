<?php
declare(strict_types=1);

namespace App\Application\Modelos;

use \Illuminate\Database\Eloquent\Model;
use App\Application\Modelos\INDICADORES\Valor_Indicadores;

class Indicadores extends Model {

    protected $table = 'indicadores';
    protected $primaryKey = 'id_indicadores';

    protected $fillable = ['responsable',
                            'corresponsable',
                            'indicador',
                            'año',
                            'en_b',
                            'en_c',
                            'feb_b',
                            'feb_c',
                            'fk_user'
                            
                        ];


   public $timestamps = false;
  /** public function Valor_Indicadores()
    *{
       * return $this->hasMany(Valor_Indicadores::class,'fk_indicadores');
    *}*/ 

}