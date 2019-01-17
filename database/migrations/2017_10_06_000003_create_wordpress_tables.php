<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Tests\Unit\wpdb;
use Tests\Unit\SqliteCreateQuery;

class CreateWordpressTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('wp_users')) {
            if (isset($GLOBALS['argv'][2]) && $GLOBALS['argv'][2] === '--database=test_mysql') {
                $config_file_path = base_path() .
                    '/wordpress/wp-tests-config.php';
                $multisite = 0;
                system('php ' .
                    escapeshellarg(base_path() .
                        '/vendor/wp-phpunit/wp-phpunit/includes/install.php') .
                    ' ' . escapeshellarg($config_file_path) . ' ' . $multisite, $retval);

            } elseif (env('DB_CONNECTION') === 'sqlite_testing') {
                global $wpdb;
                $wpdb = new wpdb(env('WP_DB_PREFIX'));

                /** Dummy function used only for being able to retrieve the wordpress schema. */
                if (!function_exists('is_multisite')) {
                    function is_multisite(): bool
                    {
                        return false;
                    }
                }
                require_once base_path() . '/wordpress/wp/wp-admin/includes/schema.php';
                $queryMysql = wp_get_db_schema('');

                $queryArray = explode(';' . "\n", trim($queryMysql));
                foreach ($queryArray as $query) {
                    $query_parser = new SqliteCreateQuery();
                    $queriesSqlite = $query_parser->rewrite_query($query);
                    foreach ($queriesSqlite as $querySqlite) {
                        DB::unprepared($querySqlite);
                    }
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $wpdb = new wpdb(env('WP_DB_PREFIX'));

        foreach ($wpdb as $table) {
            Schema::dropIfExists($table);
        }
    }
}
