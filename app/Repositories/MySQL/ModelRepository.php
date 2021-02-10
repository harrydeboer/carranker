<?php

declare(strict_types=1);

namespace App\Repositories\MySQL;

use App\Models\MySQL\Model;
use App\Repositories\Interfaces\ModelWriteRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ModelRepository extends AbstractCarRepository implements ModelWriteRepositoryInterface
{
    public function __construct(
        private Model $model,
    ) {
    }

    public function all(): Collection
    {
        return Model::all();
    }

    public function get(int $id): Model
    {
        return $this->model->findOrFail($id);
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
