<?php

namespace App\Http\Controllers;

use App\ClassModel;
use App\ClassParam;
use Illuminate\Http\Request;

class ClassificatoryController extends Controller
{
    function main () {
        return view('welcome');
    }

    function classesPage () {
        return view('classes');
    }

    function addClass () {

    }

    function computeClass (Request $request) {
        $all_fields = $request->all();
        unset($all_fields["_token"]);

        $first_param_value = reset($all_fields);
        $first_param_key = key($all_fields);

        unset($all_fields[$first_param_key]);

        $computedClasses = ClassParam::whereHas('param', function ($query) use ($first_param_key) {
            $query
                ->where('name', $first_param_key);
        })
            ->whereHas('value', function ($query) use ($first_param_value) {
                $query
                    ->where('max', '>=', $first_param_value)
                    ->where('min', '<=', $first_param_value);
            });

        foreach($all_fields as $key => $value) {
            $computedClasses = $computedClasses->whereHas('param', function ($query) use ($key) {
                $query
                    ->where('name', $key);
            })
                ->whereHas('value', function ($query) use ($value) {
                    $query
                        ->where('max', '>=', $value)
                        ->where('min', '<=', $value);
                });
        }

        return dd($computedClasses->toSql());

        $computedClasses = $computedClasses->get();


        return $computedClasses;
    }
}
