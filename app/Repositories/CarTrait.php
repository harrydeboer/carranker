<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Aspect;
use App\Models\BaseModel;
use App\Models\Rating;

/** Both Model and Trim can update their rating. Their repositories use this trait. */
trait CarTrait
{
    /** When a user rates a trim the model and trim rating are updated.
     * The update depends on whether a user has rated the car earlier or not. */
    public function updateCarRating(BaseModel $car, array $rating, ?Rating $earlierRating)
    {
        if ($this->modelClassName !== get_class($car)) {
            throw new \Exception("Wrong class of car inserted.");
        }

        $votes = $car->getVotes();
        if (is_null($earlierRating)) {
            $car->setVotes($votes + 1);
            $votes = $car->getVotes();
        }

        foreach (Aspect::getAspects() as $aspect) {
            $ratingModel = $car->getAspect($aspect);
            if (is_null($earlierRating)) {
                $ratingModel = (($votes - 1) * $ratingModel + $rating[$aspect]) / $votes;
            } else {
                $ratingModel = ($votes * $ratingModel + $rating[$aspect] - $earlierRating->getAspect($aspect)) / $votes;
            }
            $car->setAspect($aspect, $ratingModel);
        }

        $this->update($car);
    }
}