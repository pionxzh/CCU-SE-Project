<?php

/* Hawa */



use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Employee extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function(Blueprint $table)
        {
            $table ->increments('id');
            $table ->integer('uid');
            $table ->boolean('is_active') ->default(false);
            $table ->string('firstname') ->nullable() ->default(NULL);
            $table ->string('lastname') ->nullable() ->default(NULL);
            $table ->string('studentID') ->nullable() ->default(NULL);
            $table ->integer('gender') ->nullable() ->default(NULL);
            $table ->string('phone') ->nullable() ->default(NULL);
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
        Schema::dropIfExists('employees');
    }
}
