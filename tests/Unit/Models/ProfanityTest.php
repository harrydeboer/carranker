<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\Profanity;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ProfanityTest extends TestCase
{
    use DatabaseMigrations;

    public function testProfanityInDB()
    {
        $profanity = Profanity::factory()->create();

        $this->assertDatabaseHas('profanities', [
            'id' => $profanity->getId(),
            'name' => $profanity->getName(),
        ]);

        $profanityDb = (new Profanity())->find($profanity->getId());
        $this->assertTrue($profanityDb->testAttributesMatchFillable());
    }
}
