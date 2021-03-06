<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('model_id')->unsigned();
            $table->integer('trim_id')->unsigned();
            $table->double('comfort')->nullable();
            $table->double('design')->nullable();
            $table->double('performance')->nullable();
            $table->double('reliability')->nullable();
            $table->double('costs')->nullable();
            $table->integer('time');
            $table->text('content')->nullable();
            $table->tinyInteger('pending')->default(1);

            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('ratings');
    }
}
