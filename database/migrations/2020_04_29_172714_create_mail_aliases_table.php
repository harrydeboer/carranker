<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMailAliasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mail_aliases', function (Blueprint $table) {
            $table->increments('id');
	        $table->integer('domain_id')->unsigned();
            $table->string('source');
            $table->string('destination');

	        $table->foreign('domain_id')->references('id')->on('mail_domains');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mail_aliases');
    }
}
