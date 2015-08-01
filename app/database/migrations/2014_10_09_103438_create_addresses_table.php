<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('addresses', function(Blueprint $table){
            $table->bigIncrements('id');
            $table->string('address1',120);
            $table->string('address2',120)->nullable();
            $table->string('address3',120)->nullable();
            $table->string('city',100);
            $table->string('state',20);
            $table->string('country');
            $table->string('postalCode',16);
            // Morphs wont work as we have Big INT IDs
//            $table->morphs('addressable');
            $table->bigInteger('addressable_id');
            $table->string('addressable_type');
            $table->index(array('addressable_id','addressable_type'));
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('addresses', function(Blueprint $table){
           $table->dropIndex(array('addressable_id','addressable_type'));
        });
		Schema::drop('addresses');
	}

}
