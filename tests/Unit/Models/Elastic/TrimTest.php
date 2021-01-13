<?php

declare(strict_types=1);

namespace Tests\Unit\Models\Elastic;

use App\Models\Trim;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TrimTest extends BaseModel
{
    use DatabaseMigrations;

    public function testSync()
    {
        $this->elasticModelSyncEloquent(Trim::factory()->create(), new \App\Models\Elastic\Trim());
    }
}
