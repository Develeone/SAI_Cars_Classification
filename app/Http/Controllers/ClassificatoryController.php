<?php

namespace App\Http\Controllers;

use App\ClassModel;
use App\ClassParam;
use App\Param;
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

        $computedClsPrmSet = ClassParam::where('id', '>', '0')
            ->with('param', 'value', '_class')
            ->get();

        $suitableClassesParams = [];

        foreach ($computedClsPrmSet as $currClsPrm) {
            foreach ($all_fields as $key => $value) {
                if (
                    $currClsPrm->param->name == $key &&
                    $currClsPrm->value->min <= $value &&
                    $currClsPrm->value->max >= $value
                )
                    array_push($suitableClassesParams, $currClsPrm);
            }
        }

        $totalParamsCount = Param::all()->count();

        $classesSuitableParamsCount = [];

        foreach ($suitableClassesParams as $clsParam) {
            $clsId = $clsParam->_class->id;

            if (!isset($classesSuitableParamsCount["$clsId"]["count"])) {
                $classesSuitableParamsCount["$clsId"]["count"] = 1;
                $classesSuitableParamsCount["$clsId"]["name"] = $clsParam->_class->name;
            } else
                $classesSuitableParamsCount["$clsId"]["count"]++;
        }

        $resultClasses = [];

        foreach ($classesSuitableParamsCount as $key => $value) {
            if ($value["count"]== $totalParamsCount)
                array_push($resultClasses, $value["name"]);
        }

        return $resultClasses;
    }
}
