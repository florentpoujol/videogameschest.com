<?php

class Create_Developer_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('Developers', function($table) {
		    // auto incremental id (PK)
		    $table->increments('id')->unsigned();
		    // $table->primary('id');
		    $table->unique('id');

		    $table->foreign('user_id')->references('id')->on('users');

		    $table->timestamps(); // created_at | updated_at DATETIME

		    $table->string('privacy');

		    $table->text('pitch');
		    
		    $table->string('logo');
		    $table->string('website');
		    $table->string('blogfeed');
		    $table->string('country');
		    
		    $table->integer('teamsize')->unsigned();
		    
		    $table->text('technologies');
		    $table->text('operatingsystems');
		    $table->text('devices');
		    $table->text('stores');

		    $table->text('socialnetworks');
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('Developers');
	}

}