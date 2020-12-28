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
        Schema::table(env('WP_DB_PREFIX') . 'users', function (Blueprint $table) {
            $table->string('remember_token')->default('');
            $table->dateTime('user_registered')->nullable()->default(Null)->change();
            $table->increments('ID')->change();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('user_login')->unique()->change();
            $table->string('user_email')->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(env('WP_DB_PREFIX') . 'users', function (Blueprint $table) {
            $table->dropColumn('remember_token');
            $table->dateTime('user_registered')->default(0)->change();
            $table->dropColumn('email_verified_at');
            $table->bigIncrements('ID')->change();
        });
    }
}
