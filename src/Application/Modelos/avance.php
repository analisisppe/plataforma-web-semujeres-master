<?php

declare(strict_types=1);

namespace App\Application\Modelos;

use \Illuminate\Database\Eloquent\Model;

class Avance extends Model {

    protected $table = 'avance';
    protected $primaryKey = 'id_avance';

    protected $fillable = ['mes',
                            'municipio',
                        'avance_entregable',
                        'monto',
                        'proyecto',
                        'descripcion',
                        'institucion',
                        'avance_finalizado',
                        'fk_id_entregable',//llaveforaneaentregable
                        'poblacion',
                        'm_t1',//mujeres-total-primeravez
                        'm_d1',//mujeres-condiscapacidad-primeravez
                        'm_i1',//mujeres-hablaindigena-primeravez
                        'h_t1',//hombres-total-primeravez
                        'h_d1',//hombres-condiscapacidad-primeravez
                        'h_i1',//hombres-hablaindigena-primeravez
                        'm_ts',//mujeres-total-seguimiento
                        'm_ds',//mujeres-condiscapacidad-seguimiento
                        'm_is',//mujeres-hablaindigena-seguimiento
                        'h_ts',//hombres-total-seguimiento
                        'h_ds',//hombres-condiscapacidad-seguimiento
                        'h_is',//hombres-hablaindigena-seguimiento
                        'm_10',//mujeres entre 10-14 años primera vez GPA
                        'h_10',//hombres entre 10-14 años primera vez GPA
                        'm_15',//mujeres entre 15-19 años primera vez GPA
                        'h_15',//hombres entre 15-19 años primera vez GPA
                        'm_ser',//mujeres servidores primera vez GPA
                        'h_ser',//hombres servidores primera vez GPA
                        'm_padres',//mujeres padres primera vez GPA
                        'h_padres', //hombres padres primera vez GPA
                        'ms_10',//mujeres entre 10-14 años seguimiento GPA
                        'hs_10',//hombres entre 10-14 años seguimiento GPA
                        'ms_15',//mujeres entre 15-19 años segumiento GPA
                        'hs_15', //mujeres entre 15-10 años seguimiento GPA
                        'ms_ser',//mujeres servidorees segumiento GPA
                        'hs_ser',//hombres servidores segumiento GPA
                        'ms_padres',//mujeres padres seguimiento GPA
                        'hs_padres' //hombres padres seguimiento GPA                        
                        ];


    public $timestamps = false;

    
}