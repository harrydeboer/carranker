<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Model;
use Illuminate\Database\Eloquent\Collection;

class ModelRepository extends CarRepository
{
    public function all(): Collection
    {
        return Model::all();
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

    public function delete(int $id): void
    {
        Model::destroy($id);
    }
}
