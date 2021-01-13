<?php

declare(strict_types=1);

namespace Tests\Unit\Models\Elastic;

use App\Models\Make;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class MakeTest extends BaseModel
{
    use DatabaseMigrations;

    public function testSync()
    {
        $this->elasticModelSyncEloquent(Make::factory()->create(), new \App\Models\Elastic\Make());
    }
}
