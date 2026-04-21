<?php

declare(strict_types=1);

namespace App\Application\Modelos\AVG;

use \Illuminate\Database\Eloquent\Model;

class AVG extends Model {

    protected $table = 'avg';

    protected $fillable = [
        'id_avg',
        'conclusion_avg'
                        ];


    public $timestamps = false;


}