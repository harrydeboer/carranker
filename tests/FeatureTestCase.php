<?php

declare(strict_types=1);

namespace Tests;

class FeatureTestCase extends TestCase
{
    protected function tearDown(): void
    {
        $this->artisan('flush:indices')->execute();
        $this->artisan("migrate:fresh --database='mysql_testing'")->execute();
        $this->artisan('flush:redis-dbs --testing')->execute();

        parent::tearDown();
    }
}
