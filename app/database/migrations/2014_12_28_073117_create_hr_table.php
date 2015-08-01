<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHrTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('client_staff', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->string('telephone');
			$table->string('email');
			$table->string('profile_pic_filename')->nullable();
			$table->string('type');
			$table->string('client_id');
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
		//
		Schema::drop('client_staff');
	}

}
