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

            /* all fields are necessary required */


            $table ->increments('id');
            $table ->integer('uid');

            $table ->string('title'); // 徵才標題
            /* for matching purposes */
            $table ->string('jobname'); // 職缺名稱
            $table ->integer('jobtype'); // 全職(1)、兼職(0)
            $table ->string('lang'); // 語言需求(encode)
            $table ->integer('upay'); // 薪水 upper_bound
            $table ->integer('dpay'); // 薪水 lower_bound

            /* other */
            $table ->string('workplace'); // 工作地點
            $table ->text('jobinfo'); // 工作描述(high flexibility)
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
