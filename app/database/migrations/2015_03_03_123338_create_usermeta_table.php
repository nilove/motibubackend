<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsermetaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('user_meta', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->default(null);
			$table->integer('last_message_read')->default(0);
			$table->integer('last_notification_seen')->default(0);
			$table->timestamps();
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
		Schema::drop('user_meta');
	}

}
