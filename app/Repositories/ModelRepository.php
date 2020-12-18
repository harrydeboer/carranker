<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\BaseModel;
use App\Models\ElasticJob;
use App\Models\Model;
use App\Models\Rating;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model as EloquentModel;

class ModelRepository extends CarRepository
{
    public function all(): Collection
    {
        return Model::all();
    }

    public function find(int $id): ?Model
    {
        return Model::find($id);
    }

    public function get(int $id): Model
    {
        return Model::findOrFail($id);
    }

    public function create(array $createArray): Model
    {
        $model = new Model($createArray);
        $model->save();

        return $model;
    }

    public function update(EloquentModel $model): void
    {
        $model->save();
    }

    public function delete(int $id): void
    {
        Model::destroy($id);
    }

    public function updateRating(Model $model, array $rating, ?Rating $earlierRating): Model
    {
        $this->setVotesAndRating($model, $rating, $earlierRating);

        $createArray = ['make_id' => null, 'model_id' => $model->getId(), 'trim_id' => null, 'action' => 'update'];
        $job = $this->elasticJobRepository->create($createArray);
        $job->save();

        return $model;
    }
}
