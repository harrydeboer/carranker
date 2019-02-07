<?php

declare(strict_types=1);

namespace Tests\Feature\Teardown;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class TeardownTest extends TestCase
{
    public function testTeardown()
    {
        DB::unprepared("DROP DATABASE " . env('TEST_DATABASE') . ";");
        DB::unprepared("CREATE DATABASE " . env('TEST_DATABASE') . ";");

        $redis = new \Redis();
        $redis->connect(env('REDIS_HOST'), (int) env('REDIS_PORT'));
        $redis->auth(env('REDIS_PASSWORD'));
        $redis->flushAll();

        $this->assertTrue(true);
    }
}