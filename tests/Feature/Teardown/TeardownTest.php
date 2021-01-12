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
    private MakeRepository $makeRepository;
    private ModelRepository $modelRepository;
    private TrimRepository $trimRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->makeRepository = $this->app->make(MakeRepository::class);
        $this->modelRepository = $this->app->make(ModelRepository::class);
        $this->trimRepository = $this->app->make(TrimRepository::class);
    }

    public function testTeardown()
    {
        DB::unprepared("DROP DATABASE " . env('TEST_DATABASE') . ";");
        DB::unprepared("CREATE DATABASE " . env('TEST_DATABASE') . ";");

        $this->makeRepository->deleteIndex();
        $this->modelRepository->deleteIndex();
        $this->trimRepository->deleteIndex();

        $this->artisan('flush:redis-dbs')->execute();

        $this->assertTrue(true);
    }
}
