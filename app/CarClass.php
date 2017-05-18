<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CarClass extends Model
{
    protected $table = 'cars_classes';
    protected $fillable = ['name', 'car_price_range_id'];

    public $timestamps = false;

    public function price()
    {
        return $this->hasOne('App\RangeValue', 'id', 'car_price_range_id');
    }
}
