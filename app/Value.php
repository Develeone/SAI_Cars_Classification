<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Value extends Model
{
    protected $table = 'values';
    protected $fillable = ['min', 'max'];

    public $timestamps = false;
}
