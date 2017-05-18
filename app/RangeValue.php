<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RangeValue extends Model
{
    protected $table = 'range_value';
    protected $fillable = ['min', 'max'];

    public $timestamps = false;

    public function minim()
    {
        return $this->hasOne('App\CarClass', 'id', 'car_price_range_id');
    }
}
