<?php

declare(strict_types=1);

namespace Tests\Unit\Models\Elasticsearch;

use App\Models\MySQL\Trim;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TrimTest extends BaseModel
{
    use DatabaseMigrations;

    public function testSync()
    {
        $this->elasticModelSyncEloquent(Trim::factory()->create(), new \App\Models\Elasticsearch\Trim());
    }
}
