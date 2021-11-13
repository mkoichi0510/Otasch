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
Route::put('/update','UserUpdateController@update')->name('update');

//Lineクライアントのデータの取得
Route::get('/linelogin/data', 'LineAPIController@getLineClientData');

//Lineアカウントが登録済みかどうかをチェック
Route::post('/linelogin/check', 'LineAPIController@checkLineAccount');

// Lineアカウントで会員登録
Route::post('/linelogin/register', 'LineAPIController@getLineUserAccount');

//Lineアカウントの登録を削除
Route::post('/linelogin/delete', 'LineAPIController@forceDelete');

//スケジュールのルーティング
Route::prefix('schedules')->group(function(){
    Route::get('/getdata', 'ScheduleController@index');
    Route::get('/getallschedule', 'ScheduleController@indexAll');
    Route::get('/getsoftdelete', 'ScheduleController@indexSoftDeleteSchedule');
    Route::post('register', 'ScheduleController@store');
    Route::put('/update/{schedule}','ScheduleController@update');
    Route::delete('/delete/{schedule}', 'ScheduleController@delete');
    Route::post('/forcedelete', 'ScheduleController@forceDelete');
});

//タスクのルーティング
Route::prefix('tasks')->group(function(){
    Route::get('/getdata/{scheduleid}', 'TaskController@index');
    Route::get('/getalldata/{scheduleid}', 'TaskController@indexIncludeSoftDelete');
    Route::get('/getcleardata/{scheduleid}', 'TaskController@indexSoftDelete');
    Route::put('/update/{task}', 'TaskController@update');
    Route::delete('/delete/{task}', 'TaskController@delete');
    Route::post('/forcedelete', 'TaskController@forceDelete');
    Route::post('/register', 'TaskController@store');
});

// //lineuserからのメッセージの受信
// Route::post('/linemessage/message', 'LineMessageApiController@getUserMessage');
// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

