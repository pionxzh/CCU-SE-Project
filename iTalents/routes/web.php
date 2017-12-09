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


/**********Hawa*********/

// 登入
Route::post('login', 'Auth\LoginController@login');
// 註冊
Route::post('register', 'Auth\RegisterController@registrate');
// 認證信箱
Route::get('verify/{emailtok}', 'Auth\RegisterController@emailverify');
// 登出
Route::get('logout', 'Auth\LoginController@logout');
// 回傳登入使用者基本資訊
Route::get('api/user', 'ApiController@getUserInfo');
// 回傳使用者(Employer、Employee)基本資訊
Route::get('api/user/personal', 'ApiController@getPersonalInfo');
// 修改使用者(Employer、Employee)資料
Route::post('api/user/personal', 'ApiController@updatePersonalInfo');







Route::get('{all}', function () {

    return view('main');

}) -> where(['all' => '.*']);




