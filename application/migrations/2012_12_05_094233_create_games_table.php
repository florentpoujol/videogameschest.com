<?php

class Create_Games_Table {

	/**
     * Make changes to the database.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function($table) {
            // auto incremental id (PK)
            $table->increments('id')->unsigned();
            // $table->primary('id');
            $table->unique('id');

            $table->integer('developer_id')->unsigned();
            $table->foreign('developer_id')->references('id')->on('developers');

            $table->timestamps(); // created_at | updated_at DATETIME

            $table->string('privacy')->default('private');

            $table->string('devstate');

            $table->text('pitch');
            
            $table->string('logo');
            $table->string('website');
            $table->string('blogfeed');
            $table->string('country');
            $table->string('publishername');
            $table->string('publisherurl');
            $table->string('soundtrackurl');
            $table->string('price');
            
            // array fields
            $table->text('languages');
            $table->text('technologies');
            $table->text('operatingsystems');
            $table->text('devices');
            $table->text('genres');
            $table->text('themes');
            $table->text('viewpoint');
            $table->text('nbplayers');
            $table->text('tags');

            // assoc array fields
            $table->text('socialnetworks');
            $table->text('stores');
            $table->text('screenshots');
            $table->text('videos');
        });
    }

    /**
     * Revert the changes to the database.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('games');
    }

}