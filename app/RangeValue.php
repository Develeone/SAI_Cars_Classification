<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RangeValue extends Model
{
    protected $table = 'range_value';
    protected $fillable = ['min', 'max'];

    public $timestamps = false;

}
