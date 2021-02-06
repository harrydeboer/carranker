<?php

declare(strict_types=1);

namespace Tests\Unit\Models\Elasticsearch;

use App\Models\MySQL\Model;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ModelTest extends BaseModel
{
    use DatabaseMigrations;

    public function testSync()
    {
        $this->elasticModelSyncEloquent(Model::factory()->create(), new \App\Models\Elasticsearch\Model());
    }
}
