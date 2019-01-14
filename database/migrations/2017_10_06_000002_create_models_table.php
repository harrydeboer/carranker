<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('models', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('make_id')->unsigned();
            $table->string('name');
            $table->string('make');
            $table->text('content')->nullable();
            $table->float('design')->nullable();
            $table->float('comfort')->nullable();
            $table->float('reliability')->nullable();
            $table->float('performance')->nullable();
            $table->float('costs')->nullable();
            $table->float('price')->nullable();
            $table->integer('votes');
            $table->string('wiki_car_model')->nullable();

            $table->foreign('make_id')->references('id')->on('makes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('models');
    }
}
