<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    protected $fillable = [
        'data',
        'descrizione',
        'ogni_anno'
    ];
    // public $timestamps = true;
}
