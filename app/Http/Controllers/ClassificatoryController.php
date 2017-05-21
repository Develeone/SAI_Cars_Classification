<?php

namespace App\Http\Controllers;

use App\ClassModel;
use App\ClassParam;
use App\Param;
use App\Value;
use Illuminate\Http\Request;

class ClassificatoryController extends Controller
{
    function main () {
        $allParams = Param::all();

        return view('welcome', [
            "params" => $allParams
        ]);
    }

    function classesPage () {
        $allClsPrms = ClassParam::select()
            ->with('param', 'value', '_class')
            ->get()
            ->groupBy("class_id");

        $allParams = Param::all();

        return view('classes', [
            "classes" => $allClsPrms,
            "params" => $allParams
        ]);
    }

    function paramsPage () {
        $allParams = Param::all();

        return view('params', [
            "params" => $allParams
        ]);
    }

    function addClass (Request $request) {
        $newClassName = strtolower($request->class_name);

        $newClassName = str_ireplace(" ", "_", $newClassName);

        if (preg_match("/[^a-z_]/i", $newClassName) || strlen($newClassName) < 1)
            Die (abort(403, 'Unauthorized action.'));

        $foundClass = ClassModel::where('name', $newClassName)->first();

        if ($foundClass)
            Die (abort(404, 'Unauthorized action.'));

        $newClass = new ClassModel;
        $newClass->name = $newClassName;
        $newClass->save();

        $allParams = Param::all();

        foreach ($allParams as $param) {
            $newValue = new Value;
            $newValue->min = 0;
            $newValue->max = 0;
            $newValue->save();

            $newClassParam = new ClassParam();
            $newClassParam->class_id = $newClass->id;
            $newClassParam->value_id = $newValue->id;
            $newClassParam->param_id = $param->id;

            $newClassParam->save();
        }

        return $newClassName;
    }

    function addParam (Request $request) {
        $newParamName = strtolower($request->param_name);

        $newParamName = str_ireplace(" ", "_", $newParamName);

        if (preg_match("/[^a-z_]/i", $newParamName) || strlen($newParamName) < 1)
            Die (abort(403, 'Unauthorized action.'));

        $foundParam = Param::where('name', $newParamName)->first();

        if ($foundParam)
            Die (abort(404, 'Unauthorized action.'));


        $newParam = new Param;
        $newParam->name = $newParamName;
        $newParam->save();

        $allClasses = ClassModel::all();

        foreach ($allClasses as $class) {
            $newValue = new Value;
            $newValue->min = 0;
            $newValue->max = 0;
            $newValue->save();

            $newClassParam = new ClassParam();
            $newClassParam->class_id = $class->id;
            $newClassParam->value_id = $newValue->id;
            $newClassParam->param_id = $newParam->id;

            $newClassParam->save();
        }

        return $newParamName;
    }

    function deleteClass (Request $request) {
        ClassParam::where("class_id", $request->class_id)->delete();
        ClassModel::where('id', $request->class_id)->delete();
    }

    function deleteParam (Request $request) {
        ClassParam::where("param_id", $request->param_id)->delete();
        Param::where('id', $request->param_id)->delete();
    }

    function updateClassParam (Request $request) {
        if ($request->max < $request->min)
            abort(403);

        $value_id = $request->value_id;

        $editingValue = Value::where('id', $value_id)->first();

        $editingValue->min = $request->min;
        $editingValue->max = $request->max;

        $editingValue->save();

        return "Ok";
    }

    function computeClass (Request $request) {
        $all_fields = $request->all();
        unset($all_fields["_token"]);

        foreach ($all_fields as $key => $value) {
            if (is_null($value))
                unset($all_fields[$key]);
        }

        $computedClsPrmSet = ClassParam::where('id', '>', '0')
            ->with('param', 'value', '_class')
            ->get();

        $suitableClassesParams = [];

        foreach ($computedClsPrmSet as $currClsPrm) {
            foreach ($all_fields as $key => $value) {
                if (
                    $currClsPrm->param->name == $key &&
                    $currClsPrm->value->min <= $value &&
                    $currClsPrm->value->max >= $value &&
                    !is_null($value)
                )
                    array_push($suitableClassesParams, $currClsPrm);
            }
        }

        $totalParamsCount = count($all_fields);

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
