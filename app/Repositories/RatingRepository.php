<?php

namespace App\Repositories;

use App\Forms\RatingForm;
use App\Models\Elastic\Model;
use App\Models\Rating;
use App\Models\Trim;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class RatingRepository extends BaseRepository
{
    public function findRecentReviews($limit): Collection
    {
        return Rating::whereNotNull('content')->take($limit)->orderBy('time', 'desc')->get();
    }

    public function createRating(Authenticatable $user, Model $model, Trim $trim, RatingForm $form): Rating
    {
        $createArray = [
            'user_id' => $user->getId(),
            'model_id' => $model->getId(),
            'trim_id' => $trim->getId(),
            'time' => time(),
        ];
        if (is_null($form->content)) {
            $createArray['content'] = null;
        } else {
            $createArray['content'] = mb_convert_encoding($form->content, 'HTML-ENTITIES', 'ISO-8859-1');
        }
        foreach ($form->star as $key => $aspect) {
            $createArray[$key] = (int) $aspect;
        }

        return $this->create($createArray);
    }

    public function updateRating(Rating $rating, RatingForm $form): Rating
    {
        foreach ($form->star as $key => $aspect) {
            $rating->setAspect($key, (int) $aspect);
        }
        if (is_null($form->content)) {
            $rating->setContent();
        } else {
            $rating->setContent($form->content);
        }
        $this->update($rating);

        return $rating;
    }

    /** The most recent reviews for the modelpage are retrieved and paginated. */
    public function getReviews(Model $model, int $numReviewsPerModelpage): LengthAwarePaginator
    {
        return Rating::whereNotNull('content')
            ->where('model_id', $model->getId())
            ->orderBy('time', 'desc')
            ->paginate($numReviewsPerModelpage);
    }

    public function getNumOfReviews(Model $model): int
    {
        return count(Rating::whereNotNull('content')->where('model_id', $model->getId())->get());
    }
}
