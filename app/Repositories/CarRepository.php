<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Aspect;
use App\Models\BaseModel;
use App\Models\Rating;
use App\Models\ElasticJob;

/** Both Model and Trim can update their rating. Their repositories extend this class. */
abstract class CarRepository extends BaseRepository
{
    /** When a user rates a trim the model and trim rating are updated.
     * The update depends on whether a user has rated the car earlier or not. */
    public function updateCarRating(BaseModel $car, array $rating, ?Rating $earlierRating): BaseModel
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

        $classNameArray = explode('\\', $this->modelClassName);
        $model = end($classNameArray);

        $modelId = null;
        $trimId = null;
        if ($model === 'Model') {
            $modelId = $car->getId();
        } elseif ($model === 'Trim') {
            $trimId = $car->getId();
        }

        $job = new ElasticJob(['make_id' => null, 'model_id' => $modelId, 'trim_id' => $trimId, 'action' => 'update']);
        $job->save();

        return $car;
    }
}
