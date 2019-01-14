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
        $dotenv = new Dotenv\Dotenv(base_path());
        $envNames = $dotenv->getEnvironmentVariableNames();

        $dotenv = new Dotenv\Dotenv(base_path(), '.env.example');
        $envExampleNames = $dotenv->getEnvironmentVariableNames();

        $this->assertEquals($envNames, $envExampleNames);
    }

    public function testEnvWordpress()
    {
        $dotenv = new Dotenv\Dotenv(base_path() . '/wordpress');
        $envNames = $dotenv->getEnvironmentVariableNames();

        $dotenv = new Dotenv\Dotenv(base_path() . '/wordpress', '.env.example');
        $envExampleNames = $dotenv->getEnvironmentVariableNames();

        $this->assertEquals($envNames, $envExampleNames);
    }
}