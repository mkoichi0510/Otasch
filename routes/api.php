<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// 会員登録
Route::post('/register', 'Auth\RegisterController@register')->name('register');

// ログイン
Route::post('/login', 'Auth\LoginController@login')->name('login');

// ログアウト
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

// ログインユーザー
Route::get('/user', fn() => Auth::user())->name('user');

//ユーザー情報変更
Route::put('/update','UserUpdateController@updateUser')->name('update');

//Lineクライアントのデータの取得
Route::get('/linelogin/data', 'LineAPIController@getLineClientData');

// Lineアカウントで会員登録
Route::post('/linelogin/register', 'LineAPIController@getLineUserAccount');

//Lineアカウントの登録を削除
Route::post('/linelogin/delete', 'LineAPIController@forceDelete');

//スケジュールのルーティング
Route::prefix('schedules')->group(function(){
    Route::get('/getdata', 'ScheduleController@indexUnCompleteSchedule');
    Route::get('/getallschedule', 'ScheduleController@indexAllSchedule');
    Route::get('/getsoftdelete', 'ScheduleController@indexCompleteSchedule');
    Route::post('register', 'ScheduleController@storeSchedule');
    Route::put('/update/{schedule}','ScheduleController@updateSchedule');
    Route::delete('/delete/{schedule}', 'ScheduleController@softDeleteSchedule');
    Route::post('/forcedelete', 'ScheduleController@forceDeleteSchedule');
});

//タスクのルーティング
Route::prefix('tasks')->group(function(){
    Route::get('/getdata/{scheduleid}', 'TaskController@indexUnCompleteTask');
    Route::get('/getcleardata/{scheduleid}', 'TaskController@indexSoftDeleteTask');
    Route::get('/getalldata/{scheduleid}', 'TaskController@indexAllTask');
    Route::post('/register', 'TaskController@storeTask');
    Route::delete('/delete/{task}', 'TaskController@softDeleteTask');
    Route::post('/forcedelete', 'TaskController@forceDeleteTask');
    Route::put('/update/{task}', 'TaskController@updateTask');
});


