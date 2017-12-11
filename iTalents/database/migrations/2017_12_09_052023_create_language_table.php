<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLanguageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('languages', function (Blueprint $table)
        {
            $table->increments('id');
            $table->integer('uid'); // User ID
            $table->string('language'); // 語言(encode過後)
            $table->integer('langAbility');  // 語言能力分級
            $table->timestamps();
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
        Schema::dropIfExists('languages');
    }
}
