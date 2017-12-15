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




/**********(使用者)共同介面*********/

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
// 回傳單筆徵才資訊
Route::get('api/recruit/{rid}', 'ApiController@getThisRecruitment');



/***********廠商介面***********/

// 回傳廠商所有徵才資訊
Route::get('api/recruit', 'EmployerController@getAllRecruitments');
// 建立新的一筆 recruitments data
Route::post('api/recruit', 'EmployerController@newRecruitment');
// 更新工作基本欄位
Route::post('recruit/{rid}/field', 'EmployerController@updateJobField');
// 更新工作內容
Route::post('recruit/{rid}/jobinfo', 'EmployerController@updateJobInfo');
// 更新工作需求
Route::post('recruit/{rid}/jobrequire', 'EmployerController@updateJobRequire');
// 更新工作福利
Route::post('recruit/{rid}/benefits', 'EmployerController@updateCompanyBenefits');
// 更新聯絡方式
Route::post('recruit/{rid}/contact', 'EmployerController@updateCompanyContact');
// 回傳指定學生之履歷表
Route::get('api/resume/{uid}', 'EmployerController@getThisResume');
// 查看主動配對外籍生
Route::get('api/recruit/match', 'EmployerController@getRecruitMatch');
// 送出申請
Route::post('invite/{uid}', 'EmployerController@inviteThisEmployee');
// 回傳所有投過該徵才表的外籍生、我送出的邀請
Route::get('api/recruit/histroy', 'EmployerController@getRecruitHistory');



/***********外籍生介面***********/

// 回傳當前使用者履歷資料
Route::get('api/resume', 'EmployeeController@getUserResume');
// 修改履歷-基本資料
Route::post('/resume/basic', 'EmployeeController@updateBasic');
// 修改履歷-學歷經驗
Route::post('/resume/background', 'EmployeeController@updateBackground');
// 修改履歷-語言能力
Route::post('/resume/language', 'EmployeeController@updateLanguage');
// 修改履歷-求職條件
Route::post('/resume/condition', 'EmployeeController@updateCondition');
// 修改履歷-技能與證照
Route::post('/resume/skill', 'EmployeeController@updateSkill');
Route::post('/resume/certificate', 'EmployeeController@updateCertificate');
// 修改履歷-自傳
Route::post('/resume/bio', 'EmployeeController@updateBio');
// 查看主動配對廠商徵才結果
Route::get('api/resume/match', 'EmployeeController@getResumeMatch');
// 投出履歷表
Route::post('throw/{rid}', 'EmployeeController@throwThisRecruitment');
// 回傳所有該使用者投過的徵才表、廠商邀請
Route::get('api/resume/histroy', 'EmployeeController@getResumeHistory');


Route::get('{all}', function () {

    return view('main');

}) -> where(['all' => '.*']);
