<?php

declare(strict_types=1);

namespace Tests\Unit\Models\Elasticsearch;

use App\Models\MySQL\Make;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class MakeTest extends BaseModel
{
    use DatabaseMigrations;

    public function testSync()
    {
        $this->elasticModelSyncEloquent(Make::factory()->create(), new \App\Models\Elasticsearch\Make());
    }
}
