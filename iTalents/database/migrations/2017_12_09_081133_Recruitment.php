<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Recruitment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recruitments', function(Blueprint $table)
        {

            /**************************************************************************/
            $table ->increments('id');
            $table ->integer('uid');
            $table ->boolean('is_complete') ->default(false);
            /**************************************************************************/
            $table ->string('title') ->nullable() ->default(NULL); // 徵才標題
            $table ->string('jobname')->nullable() ->default(NULL); ; // 職缺名稱, (matching)
            $table ->integer('jobtype')->nullable() ->default(NULL); ; // 全職(1)、兼職(0), (matching)
            $table ->string('lang')->nullable() ->default(NULL); ; // 語言需求(encode), (matching)
            $table ->integer('upay')->nullable() ->default(NULL); ; // 薪水 upper_bound, (matching)
            $table ->integer('dpay')->nullable() ->default(NULL); ; // 薪水 lower_bound, (matching)
            $table ->mediumText('jobinfo')->nullable() ->default(NULL); ; // 工作描述, 地點, 性質, 時段, 休假制度,etc.
            /**************************************************************************/
            $table ->mediumText('jobrequire')->nullable() ->default(NULL); ; // 條件要求, 學歷, 經歷, 語文條件, 擅長工具, etc
            /**************************************************************************/
            $table ->mediumText('benefits')->nullable() ->default(NULL); ; // 工作福利, 勞保, 年終, 節慶獎金, etc
            /**************************************************************************/
            $table ->mediumText('contact')->nullable() ->default(NULL); ; // 聯繫方式, 聯絡電話, 人事聯絡人, etc
            /**************************************************************************/
            $table ->timestamps();
        }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recruitments');
    }
}
