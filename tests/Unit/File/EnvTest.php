<?php

declare(strict_types=1);

namespace Tests\Unit\File;

use Tests\TestCase;
use Dotenv;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class EnvTest extends TestCase
{
    use DatabaseMigrations;

    public function testEnvLaravel()
    {
        $dotenv = Dotenv\Dotenv::create(base_path(), '.env');
        $dotenv->load();
        $envNames = $dotenv->getEnvironmentVariableNames();

        $dotenv = Dotenv\Dotenv::create(base_path(), '.env.example');
        $dotenv->load();
        $envExampleNames = $dotenv->getEnvironmentVariableNames();

        $this->assertEquals($envNames, $envExampleNames);
    }
}