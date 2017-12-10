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


/**********使用者*********/

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



/***********廠商***********/

// 回傳廠商所有徵才資訊
Route::get('api/recruit', 'EmployerController@getAllRecruitments');

// 回傳單筆徵才資訊
Route::get('api/recruit/{rid}', 'EmployerController@getThisRecruitment');

// 建立新的一筆 recruitments data
Route::post('api/recruit', 'EmployerController@newRecruitment');

// 更新工作內容

Route::post('recruit/{rid}/field', 'EmployerController@updateJobField');

Route::post('recruit/{rid}/jobinfo', 'EmployerController@updateJobInfo');
// 更新工作需求
Route::post('recruit/{rid}/jobrequire', 'EmployerController@updateJobRequire');
// 更新工作福利
Route::post('recruit/{rid}/benefits', 'EmployerController@updateCompanyBenefits');
// 更新聯絡方式
Route::post('recruit/{rid}/contact', 'EmployerController@updateCompanyContact');


Route::get('{all}', function () {

    return view('main');

}) -> where(['all' => '.*']);




