<?php

declare(strict_types=1);

namespace App\Application\Modelos\ODS;

use \Illuminate\Database\Eloquent\Model;

class ODS extends Model {

    protected $table = 'ods';

    protected $fillable = [
        'id',
        'conclusion'
                        ];


    public $timestamps = false;


}