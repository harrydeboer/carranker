<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElasticJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('elastic_jobs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('make_id')->unsigned()->nullable();
            $table->integer('model_id')->unsigned()->nullable();
            $table->integer('trim_id')->unsigned()->nullable();
            $table->enum('action', ['create', 'update', 'delete']);

            $table->foreign('make_id')->references('id')->on('makes');
            $table->foreign('model_id')->references('id')->on('models');
            $table->foreign('trim_id')->references('id')->on('trims');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('elastic_jobs');
    }
}
