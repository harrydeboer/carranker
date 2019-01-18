<?php

namespace App\Repositories;

use App\Models\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ModelRepository extends BaseRepository
{
    use CarTrait;

    public function getModelnames(): array
    {
        $models = Model::all();
        $modelnames = [];
        foreach($models as $model) {
            $modelnames[] = $model->getMakename() . ';' . $model->getName();
        }

        return $modelnames;
    }

    public function findModelsForSearch(string $searchString): Collection
    {
        $words = explode(' ', $searchString);

        $queryObj = Model::orderBy('name', 'asc');
        foreach ($words as $word) {
            $queryObj->orWhere('name', 'like', "%$word%");
        }

        return $queryObj->get();
    }

    public function getByMakeModelName(string $makename, string $modelname): Model
    {
        $result = Model::where(['make' => $makename, 'name' => $modelname])->first();

        if (is_null($result)) {
            abort(404, "The requested model does not exist.");
        }

        return $result;
    }

    public function getReviews(Model $model, int $numReviewsPerModelpage): LengthAwarePaginator
    {
        return $model->hasMany('\App\Models\Rating', 'model_id', 'id')
            ->whereNotNull('content')
            ->orderBy('time', 'desc')
            ->paginate($numReviewsPerModelpage);
    }

    public function getNumOfReviews(Model $model): int
    {
        return count($model->hasMany('\App\Models\Rating', 'model_id', 'id')
            ->whereNotNull('content')->get());
    }

    public function getPageNumber(array $query): int
    {
        if ($query === []) {
            return 1;
        }

        return (int) $query['page'];
    }
}
