<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Aspects;
use App\Models\Model;
use App\Models\Rating;
use App\Models\Trim;

/**
 * Both a Model and a Trim can update their rating. Their repositories extend this class.
 */
abstract class CarRepository implements IRepository
{
    public function updateVotesAndRating(Model|Trim $car, array $rating, ?Rating $earlierRating): void
    {
        $votes = $car->getVotes();

        if (is_null($earlierRating)) {
            $car->setVotes($votes + 1);
        }

        $votes = $car->getVotes();

        foreach (Aspects::getAspects() as $aspect) {
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
