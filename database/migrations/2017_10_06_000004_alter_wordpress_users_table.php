<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterWordpressUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wp_users', function (Blueprint $table) {
            $table->string('remember_token', 100)->nullable();
            $table->dateTime('user_registered')->nullable()->default(Null)->change();
            $table->increments('ID')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wp_users', function (Blueprint $table) {
            $table->dropColumn('remember_token');
            $table->dateTime('user_registered')->default(0)->change();
            $table->bigIncrements('ID')->change();
        });
    }
}
