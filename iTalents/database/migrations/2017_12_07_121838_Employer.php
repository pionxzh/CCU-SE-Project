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
            $table ->boolean('is_active') ->default(false);  // 通過認證(email)
            $table ->boolean('is_verify') ->default(false); // 通過核准(company)
            $table ->string('name') ->nullable() ->default(NULL); // 公司名稱
            $table ->string('EIN') ->nullable() ->default(NULL); // 公司統編
            $table ->string('email') ->nullable() ->default(NULL); // 人事部Email
            $table ->string('phone') ->nullable() ->default(NULL);  // 人事部電話
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
