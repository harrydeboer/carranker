<?php

namespace Tests\Unit\Models;

use App\Models\Aspect;
use App\Models\Rating;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RatingTest extends TestCase
{
    use DatabaseMigrations;

    public function testRatingInDB()
    {
        $user = factory('App\User')->create();
        $trim = factory('App\Models\Trim')->create();
        $rating = factory(Rating::class)->create([
            'user_id' => $user->getid(),
            'model_id' => $trim->getModel()->getId(),
            'trim_id' => $trim->getid(),
        ]);

        $assertArray = [
            'user_id' => $rating->getUser()->getId(),
            'model_id' => $rating->getModel()->getId(),
            'trim_id' => $rating->getTrim()->getId(),
            'time' => $rating->getTime(),
            'content' => $rating->getContent(),
            ];
        foreach (Aspect::getAspects() as $aspect) {
            $assertArray[$aspect] = $rating->getAspect($aspect);
        }
        $this->assertDatabaseHas('ratings', $assertArray);

        $ratingDB = Rating::find($rating->getId());
        $this->assertTrue($ratingDB->testAttributesMatchFillable());
    }
}