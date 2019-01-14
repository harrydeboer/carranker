<?php

declare(strict_types=1);

class TeardownTest extends WP_UnitTestCase
{
    public static function tearDownAfterClass()
    {
        parent::tearDownAfterClass();

        global $wpdb;

        $wpdb->query("DROP DATABASE " . DB_NAME);
        $wpdb->query("CREATE DATABASE " . DB_NAME);
    }

    public function testDummy()
    {
        $this->assertTrue(true);
    }
}