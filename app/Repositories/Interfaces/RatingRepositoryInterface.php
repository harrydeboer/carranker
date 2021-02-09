<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Models\Elasticsearch\Model;
use App\Models\MySQL\Model as ModelEloquent;
use App\Models\MySQL\Trim;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Pagination\LengthAwarePaginator as LengthAwarePaginatorContract;
use App\Models\MySQL\Rating;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface RatingRepositoryInterface
{
    public function all(): Collection;

    public function get(int $id): Rating;

    public function create(array $createArray): Rating;

    public function delete(int $id): void;

    public function approve(int $id): void;

    /**
     * @return LengthAwarePaginator
     */
    public function findPendingReviews(int $numReviewsPerPage): LengthAwarePaginatorContract;

    public function findRecentReviews(int $limit): Collection;

    public function createRating(Authenticatable $user, ModelEloquent $model,
        Trim $trim, array $data, int $pending): Rating;

    public function updateRating(Rating $rating, array $data, int $pending): Rating;

    public function findEarlierByTrimAndUser(int $trimId, int $userId): ?Rating;

    /**
     * @return LengthAwarePaginator
     */
    public function getReviews(Model $model, int $numReviewsPerModelPage): LengthAwarePaginatorContract;

    public function getNumOfReviews(Model $model): int;
}
