<?php

declare(strict_types=1);

use Tests\Unit\wpdb;

class CarrankerThemeTest extends WP_UnitTestCase
{
    public function testWpdbDummyMatchesRealWpdb()
    {
        global $wpdb;

        require_once dirname(__DIR__, 6) . '/tests/Unit/wpdb.php';
        $wpdbDummy = new wpdb($wpdb->prefix);

        /** Test if all wpdb tables are present in wpdbdummy */
        foreach ($wpdb->tables as $key => $table) {
            $this->assertSame($wpdb->prefix . $table, $wpdbDummy->$table);
        }
        foreach ($wpdb->global_tables as $key => $table) {
            $this->assertSame($wpdb->prefix . $table, $wpdbDummy->$table);
        }
        foreach ($wpdb->ms_global_tables as $key => $table) {
            $this->assertSame($wpdb->prefix . $table, $wpdbDummy->$table);
        }

        /** Test if all wpdbdummy tables are present in wpdb */
        $wpdbDummy = new wpdb('');
        $test = true;
        foreach ($wpdbDummy as $table => $property) {
            if (!in_array($table, $wpdb->tables) && !in_array($table, $wpdb->global_tables) && !in_array($table, $wpdb->ms_global_tables)) {
                $test = false;
            }
        }
        $this->assertTrue($test);
    }
}
