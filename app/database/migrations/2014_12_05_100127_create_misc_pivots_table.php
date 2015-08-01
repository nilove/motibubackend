<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMiscPivotsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//

		Schema::create('agency_to_industry', function(Blueprint $table)
		{
			$table->integer('agency_id');
			$table->integer('industry_id');
		});

		Schema::create('agency_to_service', function(Blueprint $table)
		{
			$table->integer('agency_id');
			$table->integer('service_id');
		});

		// Schema::create('agent_to_job', function(Blueprint $table)
		// {
		// 	$table->integer('user_id');
		// 	$table->integer('job_id');
		// });

		Schema::create('candidate_to_job', function(Blueprint $table)
		{
			$table->integer('user_id');
			$table->integer('job_id');
		});

		Schema::create('candidate_to_skill', function(Blueprint $table)
		{
			$table->integer('user_id');
			$table->integer('skill_id');
			$table->text('description')->nullable();
			$table->integer('level')->nullable();
		});

		Schema::create('user_to_agency', function (Blueprint $table) {
			$table->integer('user_id');
			$table->integer('agency_id');
		});

		Schema::create('skill_to_skill_category', function (Blueprint $table) {
			$table->integer('skill_id');
			$table->integer('skill_category_id');
		});

		Schema::create('skill_to_job', function (Blueprint $table) {
			$table->integer('skill_id');
			$table->integer('job_id');
			$table->string('description')->nullable();
			$table->integer('level')->nullable();
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
		Schema::drop('skill_to_job');
		Schema::drop('skill_to_skill_category');
		Schema::drop('user_to_agency');
		Schema::drop('candidate_to_skill');
		Schema::drop('candidate_to_job');
		// Schema::drop('agent_to_job');
		Schema::drop('agency_to_service');
		Schema::drop('agency_to_industry');
	}

}
