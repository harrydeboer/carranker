<?php

declare(strict_types=1);

namespace Tests\Feature\Teardown;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class TeardownTest extends TestCase
{
    public function testTeardown()
    {
        DB::unprepared("SET FOREIGN_KEY_CHECKS=0;");
        DB::unprepared("DROP DATABASE " . env('TEST_DATABASE') . ";");
        DB::unprepared("CREATE DATABASE " . env('TEST_DATABASE') . ";");
        DB::unprepared("SET FOREIGN_KEY_CHECKS=1;");

        $this->assertTrue(true);
    }
}