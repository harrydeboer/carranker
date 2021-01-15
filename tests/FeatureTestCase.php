<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;

class FeatureTestCase extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        $this->artisan('index:cars --only-delete-create-indices')->execute();
        parent::tearDown();
    }
}