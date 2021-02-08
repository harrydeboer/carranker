<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElasticsearchJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('elasticsearch_jobs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('make_id')->unsigned()->nullable();
            $table->integer('model_id')->unsigned()->nullable();
            $table->integer('trim_id')->unsigned()->nullable();
            $table->enum('action', ['create', 'update', 'delete']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('elasticsearch_jobs');
    }
}
