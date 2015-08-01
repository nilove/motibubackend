<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMiscTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('agency_locations', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('country_id');
			$table->integer('city_id');
			$table->string('address');
			$table->string('unit');
			$table->integer('floor');
			$table->string('zip_code');
			$table->integer('agency_id');
		});

		Schema::create('services', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
		});

		Schema::create('agency_contacts', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->string('department');
			$table->string('email');
			$table->string('telephone');
			$table->string('fax');
			$table->integer('agency_id');
		});

		Schema::create('skill_categories', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->string('name_de');
			$table->string('name_fr');
			$table->string('name_it');
            $table->integer('esco_id');
            $table->softDeletes();
		});

		Schema::create('skills', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->string('name_de');
			$table->string('name_fr');
			$table->string('name_it');
            $table->string('language');
            $table->string('esco_uri');
			$table->integer('skill_category_id');
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
		Schema::drop('skills');
		Schema::drop('skill_categories');
		Schema::drop('agency_contacts');
		Schema::drop('services');
		Schema::drop('agency_locations');
	}

}
