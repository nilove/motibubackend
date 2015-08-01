<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCandidatesProfileTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('candidates',function(Blueprint $table){
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->boolean('is_external_vcard')->default(false);
            $table->integer('gender_id')->nullable();
            $table->integer('date_of_birth')->nullable();
            $table->text('about')->nullable();
            $table->text('about_de')->nullable();
            $table->text('about_fr')->nullable();
            $table->text('about_it')->nullable();
            $table->string('residency')->nullable();
            $table->string('telephone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('years_of_experience')->nullable();
            $table->string('nationality')->nullable();
            $table->boolean('has_work_permit')->nullable();
            $table->boolean('is_married')->nullable();
            $table->integer('num_children')->nullable();
            $table->boolean('has_drivers_license')->nullable();
            $table->boolean('is_available')->nullable();
            $table->boolean('is_employed')->nullable();
			$table->string('social_facebook')->nullable();
			$table->string('social_linked_in')->nullable();
			$table->string('social_twitter')->nullable();
			$table->string('social_google_plus')->nullable();
			$table->string('social_instagram')->nullable();
			$table->string('social_youtube')->nullable();
            $table->string('profile_pic_filename')->nullable();
            $table->string('inline_skills')->nullable();
            $table->string('location_name')->nullable();
            $table->integer('location_latitude')->nullable();
            $table->integer('location_longitude')->nullable();
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
		Schema::drop('candidates');
	}

}
