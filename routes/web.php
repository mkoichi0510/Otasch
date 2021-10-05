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
// Route::group(['middleware' => 'auth'], function() {
//     Route::get('/home', fn() => view('index'));
// });

Route::prefix('schedules')->group(function(){
    Route::get('', 'ScheduleController@index'); 
    Route::post('', 'ScheduleController@store');
    Route::put('/{schedule}','ScheduleController@update');
    Route::delete('/{schedule}', 'ScheduleController@delete');
});

Route::get('/{any?}', fn() => view('index'))->where('any', '.+');
