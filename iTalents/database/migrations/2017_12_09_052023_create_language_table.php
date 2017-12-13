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
            $table ->increments('id');
            $table ->integer('uid');
            $table ->integer('en') ->default(0); // 英文
            $table ->integer('ch') ->default(0); // 中文
            $table ->integer('jp') ->default(0); // 日文
            $table ->integer('fr') ->default(0); // 德文
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
        Schema::dropIfExists('languages');
    }
}
