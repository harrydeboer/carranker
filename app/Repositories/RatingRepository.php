<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Validators\RatingValidator;
use App\Models\Rating;
use App\Models\Model as ModelEloquent;
use App\Models\Elastic\Model;
use App\Models\Trim;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class RatingRepository implements IRepository
{
    public function all(): Collection
    {
        return Rating::all();
    }

    public function get(int $id): Rating
    {
        return Rating::findOrFail($id);
    }

    public function create(array $createArray): Rating
    {
        $model = new Rating($createArray);
        $model->save();

        return $model;
    }

    public function delete(int $id): void
    {
        Rating::destroy($id);
    }

    public function approve(int $id): void
    {
        $review = $this->get($id);
        $review->setPending(0);
        $review->save();
    }

    public function findPendingReviews(int $numReviewsPerPage): LengthAwarePaginator
    {
        return Rating::whereNotNull('content')
            ->where('pending', 1)
            ->orderBy('time', 'desc')
            ->paginate($numReviewsPerPage);
    }

    public function findRecentReviews(int $limit): Collection
    {
        return Rating::whereNotNull('content')->where('pending', 0)->take($limit)->orderBy('time', 'desc')->get();
    }

    public function createRating(Authenticatable $user, ModelEloquent $model,
        Trim $trim, RatingValidator $form, int $pending): Rating
    {
        $createArray = [
            'user_id' => $user->getId(),
            'model_id' => $model->getId(),
            'trim_id' => $trim->getId(),
            'time' => time(),
            'pending' => $pending,
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

    public function updateRating(Rating $rating, RatingValidator $form, int $pending): Rating
    {
        foreach ($form->star as $key => $aspect) {
            $rating->setAspect($key, (int) $aspect);
        }
        if (is_null($form->content)) {
            $rating->setContent();
        } else {
            $rating->setContent($form->content);
        }
        $rating->setPending($pending);

        $rating->save();

        return $rating;
    }

    public function findEarlierByTrimAndUser(int $trimId, int $userId): ?Rating
    {
        $ratings = Rating::where('trim_id', $trimId)
            ->where('user_id', $userId)
            ->where('pending', 0)
            ->orderBy('time', 'asc')
            ->get();

        if (count($ratings) === 1) {
            return null;
        }

        return $ratings->first();
    }

    /** The most recent reviews for the modelpage are retrieved and paginated. */
    public function getReviews(Model $model, int $numReviewsPerModelpage): LengthAwarePaginator
    {
        return Rating::whereNotNull('content')
            ->where('model_id', $model->getId())
            ->where('pending', 0)
            ->orderBy('time', 'desc')
            ->paginate($numReviewsPerModelpage);
    }

    public function getNumOfReviews(Model $model): int
    {
        return count(Rating::whereNotNull('content')->where('pending', 0)->where('model_id', $model->getId())->get());
    }
}
