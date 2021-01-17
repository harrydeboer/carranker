<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Rating;
use App\Models\Model as ModelEloquent;
use App\Models\Elastic\Model;
use App\Models\Trim;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator as LengthAwarePaginatorContract;

class RatingRepository implements IRepository
{
    public function __construct(
        private Rating $rating,
    ) {
    }

    public function all(): Collection
    {
        return Rating::all();
    }

    public function get(int $id): Rating
    {
        return $this->rating->findOrFail($id);
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

    /**
     * @return LengthAwarePaginator
     */
    public function findPendingReviews(int $numReviewsPerPage): LengthAwarePaginatorContract
    {
        return $this
            ->rating
            ->whereNotNull('content')
            ->where('pending', 1)
            ->orderBy('time', 'desc')
            ->paginate($numReviewsPerPage);
    }

    public function findRecentReviews(int $limit): Collection
    {
        return $this
            ->rating
            ->whereNotNull('content')
            ->where('pending', 0)
            ->take($limit)->orderBy('time', 'desc')->get();
    }

    public function createRating(Authenticatable $user, ModelEloquent $model,
        Trim $trim, array $data, int $pending): Rating
    {
        $createArray = [
            'user_id' => $user->getId(),
            'model_id' => $model->getId(),
            'trim_id' => $trim->getId(),
            'time' => time(),
            'pending' => $pending,
            'content' => $data['content']
        ];

        foreach ($data['star'] as $key => $aspect) {
            $createArray[$key] = (int) $aspect;
        }

        return $this->create($createArray);
    }

    public function updateRating(Rating $rating, array $data, int $pending): Rating
    {
        foreach ($data['star'] as $key => $aspect) {
            $rating->setAspect($key, (int) $aspect);
        }
        if (!is_null($data['content'])) {
            $rating->setContent($data['content']);
        }
        $rating->setPending($pending);

        $rating->save();

        return $rating;
    }

    public function findEarlierByTrimAndUser(int $trimId, int $userId): ?Rating
    {
        $ratings = $this
            ->rating
            ->where('trim_id', $trimId)
            ->where('user_id', $userId)
            ->where('pending', 0)
            ->orderBy('time')
            ->get();

        if (count($ratings) === 1) {
            return null;
        }

        return $ratings->first();
    }

    /**
     * The most recent reviews for the model page are retrieved and paginated.
     * @return LengthAwarePaginator
     */
    public function getReviews(Model $model, int $numReviewsPerModelPage): LengthAwarePaginatorContract
    {
        return $this
            ->rating
            ->whereNotNull('content')
            ->where('model_id', $model->getId())
            ->where('pending', 0)
            ->orderBy('time', 'desc')
            ->paginate($numReviewsPerModelPage);
    }

    public function getNumOfReviews(Model $model): int
    {
        return count($this
                         ->rating
                         ->whereNotNull('content')
                         ->where('pending', 0)
                         ->where('model_id', $model->getId())
                         ->get());
    }
}
