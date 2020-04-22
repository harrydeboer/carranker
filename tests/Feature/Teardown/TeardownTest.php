<?php

declare(strict_types=1);

namespace Tests\Feature\Teardown;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class TeardownTest extends TestCase
{
    public function testTeardown()
    {
        DB::unprepared("DROP DATABASE " . getenv('TEST_DATABASE') . ";");
        DB::unprepared("CREATE DATABASE " . getenv('TEST_DATABASE') . ";");

        $this->assertTrue(true);
    }
}