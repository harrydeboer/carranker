<?php

declare(strict_types=1);

namespace Tests\Unit\File;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Symfony\Component\Yaml\Yaml;

class ComposerTest extends TestCase
{
    use DatabaseMigrations;

    public function testVersionsWordpressTestSuiteMatch()
    {
        $string = file_get_contents(base_path() . '/composer.json');
        $jsonObject = json_decode($string);
        $requirements = $jsonObject->require;

        /**
         * The file composer.json must have the same elasticsearch version as docker-compose.yml.
         */
        $yamlArray = Yaml::parse(file_get_contents(base_path() . '/docker-compose.yml'));
        $imageArray = explode(':', $yamlArray['services']['elasticsearch']['image']);
        $this->assertEquals($requirements->{'elasticsearch/elasticsearch'}, $imageArray[1]);

        $extensions = get_loaded_extensions();
        foreach ($extensions as $extension) {
            if (
                $extension === 'Core'
                || $extension === 'standard'
                || $extension === 'xdebug'
                || $extension === 'pdo_sqlite'
                || $extension === 'sqlite3'
            ) {
                continue;
            } elseif ($extension === 'Zend OPcache') {
                $this->assertObjectHasAttribute(
                    'ext-Zend-OPcache',
                    $requirements,
                    "extension Zend-OPcache is missing in composer.json",
                );
            } else {
                $this->assertObjectHasAttribute(
                    'ext-' . $extension,
                    $requirements,
                    "extension $extension is missing in composer.json",
                );
            }
        }

        foreach ($requirements as $key => $requirement) {
            if (substr($key, 0, 4) === 'ext-') {
                $extension = substr($key, 4, strlen($key));
                if ($extension === 'Zend-OPcache') {
                    $this->assertTrue(extension_loaded('Zend OPcache'), "Extension Zend Opcache is not loaded.");
                } else {
                    $this->assertTrue(extension_loaded($extension), "Extension $extension is not loaded.");
                }
            }
        }
    }
}
