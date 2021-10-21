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

// Lineアカウントで会員登録
Route::post('/linelogin/register', 'LineAPIController@getLineUserAccount');

//Lineアカウントの登録を削除
Route::post('/linelogin/delete', 'LineAPIController@forceDelete');

//lineuserからのメッセージの受信
Route::post('/linemessage/message', 'LineMessageApiController@getUserMessage');
// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

