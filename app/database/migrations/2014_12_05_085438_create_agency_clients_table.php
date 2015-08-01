<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgencyClientsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('clients', function(Blueprint $table)
		{
			$table->increments('id');
			$table->text('name');
			$table->string('about');
			$table->string('location');
			$table->string('contact_name')->nullable();
			$table->string('contact_telephone')->nullable();
			$table->string('contact_email')->nullable();
			$table->string('logo_filename')->nullable();
			$table->integer('industry_id');
			$table->integer('agency_id');
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
		Schema::drop('clients');
	}

}
