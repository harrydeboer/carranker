<?php

declare(strict_types=1);

namespace Tests\Feature\Teardown;

use App\Repositories\Elastic\MakeRepository;
use App\Repositories\Elastic\ModelRepository;
use App\Repositories\Elastic\TrimRepository;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class TeardownTest extends TestCase
{
    public function testTeardown()
    {
        $makeRepository = new MakeRepository();
        $modelRepository = new ModelRepository();
        $trimRepository = new TrimRepository();
        DB::unprepared("DROP DATABASE " . env('TEST_DATABASE') . ";");
        DB::unprepared("CREATE DATABASE " . env('TEST_DATABASE') . ";");

        $makeRepository->deleteIndex();
        $modelRepository->deleteIndex();
        $trimRepository->deleteIndex();

        $this->artisan('flushredisdb')->execute();

        $this->assertTrue(true);
    }
}