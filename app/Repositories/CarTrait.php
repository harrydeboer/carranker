<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Aspect;

/** Both Model and Trim can update their rating. Their repositories use this trait. */
trait CarTrait
{
    /** When a user rates a trim the model and trim rating are updated.
     * The update depends on whether a user has rated the car earlier or not. */
    public function updateCarRating($car, array $rating, $earlierRating = null)
    {
        $votes = $car->getVotes();
        if (!$earlierRating) {
            $car->setVotes($votes + 1);
            $votes = $car->getVotes();
        }

        foreach (Aspect::getAspects() as $aspect) {
            $ratingModel = $car->$aspect;
            if ($earlierRating === null) {
                $ratingModel = (($votes - 1) * $ratingModel + $rating[$aspect]) / $votes;
            } else {
                $ratingModel = ($votes * $ratingModel + $rating[$aspect] - $earlierRating->$aspect) / $votes;
            }
            $car->$aspect = $ratingModel;
        }

        $this->update($car);
    }
}