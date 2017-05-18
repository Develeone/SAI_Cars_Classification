<?php

namespace App\Http\Controllers;

use App\CarClass;
use Illuminate\Http\Request;

class CarClassController extends Controller
{
    function main () {
        $carClass = CarClass::where('id', 1)->with('price')->first();
        dd($carClass->price);
        die();
        //return view('welcome');
    }
}
