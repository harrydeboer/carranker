<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Aspect;

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
            foreach (Aspect::getAspects() as $aspect) {
                $table->double($aspect)->nullable();
            }
            $table->double('price')->nullable();
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
