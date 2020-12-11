<?php

declare(strict_types=1);

namespace Tests\Unit\File;

use Dotenv\Store\File\Reader;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class EnvTest extends TestCase
{
    use DatabaseMigrations;

    public function testEnvLaravel()
    {
	    $contents = Reader::read([base_path() . "/.env.example"], true);
	    $envExampleNames = explode(PHP_EOL, $contents[base_path() . "/.env.example"]);

	    $contents = Reader::read([base_path() . "/.env"], true);
        $envNames = explode(PHP_EOL, $contents[base_path() . "/.env"]);

        $this->assertEquals(count($envNames), count($envExampleNames));

        foreach ($envExampleNames as $key => $name) {
        	if ($envNames[$key] === "") {
        		$this->assertTrue($name === "");
	        } elseif ($name === "") {
		        $this->assertTrue($envNames[$key] === "");
	        }else {
		        $this->assertTrue( strpos( $envNames[$key], $name ) === 0,
			        "the first part of $envNames[$key] is not $name");
	        }
        }
    }
}
