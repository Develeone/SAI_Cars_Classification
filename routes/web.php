<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', "ClassificatoryController@main");

Route::get('/classes', "ClassificatoryController@classesPage");
Route::get('/params',  "ClassificatoryController@paramsPage");

Route::post('/addclass', "ClassificatoryController@addClass");
Route::post('/addparam', "ClassificatoryController@addParam");
Route::post('/updateclassparam', "ClassificatoryController@updateClassParam");

Route::post('/deleteclass', "ClassificatoryController@deleteClass");
Route::post('/deleteparam', "ClassificatoryController@deleteParam");

Route::post('/classificate', "ClassificatoryController@computeClass");