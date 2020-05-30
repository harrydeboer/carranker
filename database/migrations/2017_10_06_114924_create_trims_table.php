<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Aspect;

class CreateTrimsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trims', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('model_id')->unsigned();
            $table->string('name')->nullable();
            $table->string('make');
            $table->string('model');
            $table->double('price')->nullable();
            $table->integer('votes');
            foreach (Aspect::getAspects() as $aspect) {
                $table->double($aspect)->nullable();
            }
            $table->string('framework', 50)->nullable();
            $table->string('fuel', 50)->nullable();
            $table->integer('year_begin');
            $table->integer('year_end');
            $table->integer('number_of_doors')->nullable();
            $table->integer('number_of_seats')->nullable();
            $table->integer('max_trunk_capacity')->nullable();
            $table->double('engine_capacity')->nullable();
            $table->integer('fueltank_capacity')->nullable();
            $table->integer('max_speed')->nullable();
            $table->integer('full_weight')->nullable();
            $table->integer('number_of_gears')->nullable();
            $table->string('gearbox_type', 100)->nullable();
            $table->integer('engine_power')->nullable();
            $table->double('acceleration')->nullable();
            $table->double('fuel_consumption')->nullable();

            $table->foreign('model_id')->references('id')->on('models');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trims');
    }
}
