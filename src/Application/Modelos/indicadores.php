<?php
declare(strict_types=1);

namespace App\Application\Modelos;

use \Illuminate\Database\Eloquent\Model;
use App\Application\Modelos\INDICADORES\Indicador;

class Indicadores extends Model {

    protected $table = 'indicadores';
    protected $primaryKey = 'id_indicadores';

    protected $fillable = [
        'responsable',
        'corresponsable',
        'indicador',   // INT FK -> indicador.id_indicador
        'año',
        'en_a',  'en_b',  'en_c',
        'feb_a', 'feb_b', 'feb_c',
        'mar_a', 'mar_b', 'mar_c',
        'ab_a',  'ab_b',  'ab_c',
        'may_a', 'may_b', 'may_c',
        'jun_a', 'jun_b', 'jun_c',
        'jul_a', 'jul_b', 'jul_c',
        'ago_a', 'ago_b', 'ago_c',
        'sep_a', 'sep_b', 'sep_c',
        'oct_a', 'oct_b', 'oct_c',
        'nov_a', 'nov_b', 'nov_c',
        'dic_a', 'dic_b', 'dic_c',
        'anual_a', 'anual_b', 'anual_c',
        'fk_user'
    ];

    public $timestamps = false;

    public function indicadorCatalogo()
    {
        return $this->belongsTo(Indicador::class, 'indicador', 'id_indicador');
    }

}