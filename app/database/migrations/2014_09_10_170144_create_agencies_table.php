<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgenciesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('agencies', function(Blueprint $table)
		{
			$table->bigIncrements('id');
			$table->string('name')->nullable();
			$table->string('description')->nullable();
			$table->integer('num_employees_to')->nullable();
			$table->integer('num_employees_from')->nullable();
			$table->string('legal_entity')->nullable();
			$table->string('reg_no')->nullable();
			$table->string('industry_id')->nullable();
			$table->integer('operational_hours_monday_from')->nullable();
			$table->integer('operational_hours_monday_to')->nullable();
			$table->integer('operational_hours_tuesday_from')->nullable();
			$table->integer('operational_hours_tuesday_to')->nullable();
			$table->integer('operational_hours_wednesday_from')->nullable();
			$table->integer('operational_hours_wednesday_to')->nullable();
			$table->integer('operational_hours_thursday_from')->nullable();
			$table->integer('operational_hours_thursday_to')->nullable();
			$table->integer('operational_hours_friday_from')->nullable();
			$table->integer('operational_hours_friday_to')->nullable();
			$table->integer('operational_hours_saturday_from')->nullable();
			$table->integer('operational_hours_saturday_to')->nullable();
			$table->integer('operational_hours_sunday_from')->nullable();
			$table->integer('operational_hours_sunday_to')->nullable();
			$table->string('social_facebook')->nullable();
			$table->string('social_linked_in')->nullable();
			$table->string('social_twitter')->nullable();
			$table->string('social_google_plus')->nullable();
			$table->string('social_instagram')->nullable();
			$table->string('social_youtube')->nullable();
			$table->string('banner_filename')->nullable();
			$table->string('logo_filename')->nullable();
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
		Schema::drop('agencies');
	}

}
