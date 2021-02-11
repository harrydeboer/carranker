<?php

declare(strict_types=1);

namespace Tests\Unit\Models\MySQL;

use App\Models\Traits\AspectsTrait;
use App\Models\MySQL\Rating;
use App\Models\MySQL\User;
use App\Models\MySQL\Trim;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RatingTest extends TestCase
{
    use DatabaseMigrations;

    public function testRatingInDB()
    {
        $user = User::factory()->create();
        $trim = Trim::factory()->create();
        $rating = Rating::factory()->create([
            'user_id' => $user->getid(),
            'model_id' => $trim->getModel()->getId(),
            'trim_id' => $trim->getid(),
        ]);

        $assertArray = [
            'id' => $rating->getId(),
            'user_id' => $rating->getUser()->getId(),
            'model_id' => $rating->getModel()->getId(),
            'trim_id' => $rating->getTrim()->getId(),
            'time' => $rating->getTime(),
            'content' => $rating->getContent(),
            'pending' => $rating->getPending(),
            ];

        foreach (AspectsTrait::getAspects() as $aspect) {
            $assertArray[$aspect] = $rating->getAspect($aspect);
        }

        $this->assertDatabaseHas('ratings', $assertArray);

        $ratingDb = (new Rating())->find($rating->getId());

        $this->assertTrue($ratingDb->testAttributesMatchFillable());
    }
}
