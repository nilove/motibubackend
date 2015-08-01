<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('jobs', function(Blueprint $table)
		{
            $table->bigIncrements('id');
            $table->bigInteger('agency_id');
            $table->bigInteger('client_id');
            $table->bigInteger('agent_id');
            $table->bigInteger('hr_id');
            $table->integer('mandate_start')->nullable();
            $table->integer('mandate_end')->nullable();
            $table->boolean('mandate_is_private')->default(false);
            $table->boolean('is_published')->nullable();
            $table->boolean('is_published_is_private')->default(false);
            $table->string('title');
            $table->boolean('title_is_private')->default(false);
            $table->integer('sector_id')->nullable();
            $table->boolean('sector_id_is_private')->default(false);
            $table->integer('age_range_from')->nullable();
            $table->integer('age_range_to')->nullable();
            $table->boolean('age_range_is_private')->default(false);
            $table->integer('gender_id')->nullable();
            $table->boolean('gender_id_is_private')->default(false);
            $table->integer('nationality_id')->nullable();
            $table->boolean('nationality_id_is_private')->default(false);
            $table->integer('work_permit_id')->nullable();
            $table->boolean('work_permit_id_is_private')->default(false);
            $table->integer('years_of_experience')->nullable();
            $table->boolean('years_of_experience_is_private')->default(false);
            $table->integer('min_degree_id')->nullable();
            $table->boolean('min_degree_id_is_private')->default(false);
            $table->integer('residence_id')->nullable();
            $table->boolean('residence_id_is_private')->default(false);
            $table->integer('date_of_entry')->nullable();
            $table->boolean('date_of_entry_is_private')->default(false);
            $table->integer('working_hours_from')->nullable();
            $table->integer('working_hours_to')->nullable();
            $table->boolean('working_hours_is_private')->default(false);
            $table->integer('salary_range_from')->nullable();
            $table->integer('salary_range_to')->nullable();
            $table->boolean('salary_range_is_private')->default(false);
            $table->text('about');
            $table->text('about_de');
            $table->text('about_fr');
            $table->text('about_it');
            $table->boolean('about_is_private')->default(false);
            $table->string('slug')->nullable();
            $table->string('contract_type_id')->nullable();
            $table->string('inline_skills')->nullable();
            $table->string('location_name')->nullable();
            $table->integer('location_latitude')->nullable();
            $table->integer('location_longitude')->nullable();
            $table->timestamps();
            $table->softDeletes();
		});

        Schema::create('contract_types', function(Blueprint $table) {
            $table->string('name');
        });

        // DB::statement('ALTER TABLE jobs ADD FULLTEXT search(title,description,reference,location,search_extra,meta_description,meta_keywords)');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        // Schema::table('jobs', function($table){
        //     $table->dropIndex('search');
        // });
		Schema::drop('contract_types');
        Schema::drop('jobs');
	}

}
