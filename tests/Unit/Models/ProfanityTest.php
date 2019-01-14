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
        $profanity = factory(Profanity::class)->create();

        $this->assertDatabaseHas('profanities', [
            'name' => $profanity->getName(),
        ]);

        $profanityDB = Profanity::find($profanity->getId());
        $this->assertTrue($profanityDB->testAttributesMatchFillable());
    }
}