<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResumeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resumes', function (Blueprint $table) {


            $table->increments('id');
            $table->integer('uid');
            // 基本資料
            $table->string('firstName') ->nullable() ->default(NULL);  // 英文名
            $table->string('lastName') ->nullable() ->default(NULL); // 英文姓
            $table->integer('gender') ->nullable() ->default(NULL);  // 性別
            $table->date('birth') ->nullable() ->default(NULL); // 生日，Ex: 1997/01/01
            $table->string('nation') ->nullable() ->default(NULL); // 國籍
            $table->string('email') ->nullable() ->default(NULL);  // 信箱
            $table->string('phone') ->nullable() ->default(NULL);  // 手機

            // 職業資訊
            $table->string('expectedJobName') ->nullable() ->default(NULL);  // 希望職務名稱
            $table->integer('salaryFrom') ->nullable() ->default(NULL);  // 工作薪資 從
            $table->integer('salaryTo') ->nullable() ->default(NULL);  // 工作薪資 到

            // 個人背景
            $table->mediumText('bio') ->nullable() ->default(NULL); // 自傳
            $table->mediumText('background') ->nullable() ->default(NULL); // 學歷、工作經驗
            $table->mediumText('skill') ->nullable() ->default(NULL); // 具備之技能
            $table->mediumText('certificate') ->nullable() ->default(NULL); // 具備之證書
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('resumes');
    }
}
