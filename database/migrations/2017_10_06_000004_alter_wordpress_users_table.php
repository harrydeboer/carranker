<?php

declare(strict_types=1);

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
            $table->rememberToken();
            $table->dateTime('user_registered')->nullable()->default(Null)->change();
            $table->increments('ID')->change();
            $table->timestamp('email_verified_at')->nullable();
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
            $table->dropColumn(['remember_token', 'email_verified_at']);
            $table->dropUnique(env('WP_DB_PREFIX') . 'users_user_email_unique');
            $table->dateTime('user_registered')->nullable(false)
                ->default('0000-00-00 00:00:00')->change();
            $table->bigIncrements('ID')->change();
        });
    }
}
