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
            $table->timestamps();
            $table->integer('uid');

            // basuc
            $table->string('firstName') ->nullable() ->default(NULL);  // 英文名
            $table->string('lastName') ->nullable() ->default(NULL); // 英文姓
            $table->string('pid') ->nullable() ->default(NULL);  // 身分證，Ex: A12345679
            $table->integer('gender') ->nullable() ->default(NULL);  // 身分證，Ex: A12345679
            $table->string('birthday') ->nullable() ->default(NULL); // 生日，Ex: 1997/01/01
            $table->string('nation') ->nullable() ->default(NULL); // 國籍
            $table->string('email') ->nullable() ->default(NULL);  // 信箱
            $table->string('cellphone') ->nullable() ->default(NULL);  // 手機
            $table->string('address') ->nullable() ->default(NULL);  // 地址

            // occupation
            $table->string('expectedJobName') ->nullable() ->default(NULL);  // 希望職務名稱
            $table->integer('expectedJobType') ->nullable() ->default(NULL); // 希望工作性質 0全職 1兼職
            $table->integer('SalaryFrom') ->nullable() ->default(NULL);  // 工作薪資 從
            $table->integer('SalaryTo') ->nullable() ->default(NULL);  // 工作薪資 到
            $table->string('expectedJobDec') ->nullable() ->default(NULL); // 職務內容描述
            $table->string('expectedJobCat') ->nullable() ->default(NULL); // 希望職務類別
            $table->string('expectedJobArea') ->nullable() ->default(NULL);  // 希望工作地區
            $table->integer('expectedJobTime') ->nullable() ->default(NULL); // 希望工作時段 0日班 1夜班 2大夜班 3假日班
            $table->integer('SalaryType') ->nullable() ->default(NULL);  // 希望工作待遇 0面議 1時薪 2月薪

            // background
            $table->text('background') ->nullable() ->default(NULL);

            // certificate
            $table->text('cert') ->nullable() ->default(NULL);

            // autobiography
            $table->text('bio') ->nullable() ->default(NULL);
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
