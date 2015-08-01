<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlansAndSubscriptionTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('plans', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('title');
			$table->text('description');
			$table->text('meta');
			$table->integer('cost_in_cents')->default(null);
			$table->integer('duration')->default(0);
			$table->timestamps();
		});

		Schema::create('subscriptions', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id');
			$table->integer('plan_id');
			$table->integer('expires_on');
			$table->text('metadata')->default(null);
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
		Schema::drop('subscriptions');
		Schema::drop('plans');
	}

}
