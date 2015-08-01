<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function(Blueprint $table)
        {
            $table->bigIncrements('id');
            $table->string('username')->unique()->nullable();
            $table->string('email')->unique();
            $table->string('first_name', 60);
            $table->string('last_name', 60);
            $table->string('password', 60);
            $table->string('remember_token', 60)->nullable();

            $table->boolean('confirmed')->default(0);
            $table->string('confirmation_code')->nullable();
            /* Create the polymorphic relationship */

//            $table->morphs('userable');
            $table->bigInteger('userable_id')->nullable();
            $table->string('userable_type')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }

}
