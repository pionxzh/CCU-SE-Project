<?php

/* Hawa */



use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Employer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */


    public function up()
    {
        Schema::create('employers', function(Blueprint $table)
        {

            $table ->increments('id');
            $table ->integer('uid'); // 使用者 ID
            $table ->integer('cid') ->nullable() ->default(NULL); // 公司行號
            $table ->boolean('is_active') ->default(false);  // 通過認證
            $table ->string('cname') ->nullable() ->default(NULL);
            $table ->string('cphone') ->nullable() ->default(NULL);
            $table ->string('caddress') ->nullable() ->default(NULL);
            $table ->timestamps();


        });
    }





    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employers');
    }
}
