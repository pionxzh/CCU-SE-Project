<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    /* This will be triggered when calling artisan migrate, Hawa*/
    public function up()
    {
        Schema::create('users', function (Blueprint $table)
        {

            $table ->increments('id');
            $table ->string('email') ->unique();
            $table ->string('password');
            $table ->integer('user_type');
            $table ->string('emailtok') ->nullable() ->default(NULL);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
