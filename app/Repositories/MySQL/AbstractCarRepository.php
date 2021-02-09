<?php

declare(strict_types=1);

namespace App\Repositories\MySQL;

use App\Models\MySQL\AspectsTrait;
use App\Models\MySQL\Rating;
use App\Models\Interfaces\CarInterface;

/**
 * Both a Model and a Trim can update their rating. Their repositories extend this class.
 */
abstract class AbstractCarRepository
{
    public function updateVotesAndRating(CarInterface $car, array $rating, ?Rating $earlierRating): void
    {
        $votes = $car->getVotes();

        if (is_null($earlierRating)) {
            $car->setVotes($votes + 1);
        }

        $votes = $car->getVotes();

        foreach (AspectsTrait::getAspects() as $aspect) {
            $ratingModel = $car->getAspect($aspect);
            if (is_null($earlierRating)) {
                $ratingModel = (($votes - 1) * $ratingModel + $rating[$aspect]) / $votes;
            } else {
                $ratingModel = ($votes * $ratingModel + $rating[$aspect] - $earlierRating->getAspect($aspect)) / $votes;
            }
            $car->setAspect($aspect, $ratingModel);
        }

        $car->save();
    }
}
