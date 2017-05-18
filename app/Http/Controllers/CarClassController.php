<?php

namespace App\Http\Controllers;

use App\CarClass;
use Illuminate\Http\Request;

class CarClassController extends Controller
{
    function main () {
        return view('welcome');
    }

    function computeClass (Request $request) {
        $computedClasses = CarClass::whereHas('price', function ($query) use ($request) {
            $query->where('max', '>', $request->price);
        })->get();

        if ($computedClasses->count() < 1)
            echo ("No classes found");

        foreach ($computedClasses as $class) {
            echo("Class: ".$class->name."<br>");
        }
    }
}
