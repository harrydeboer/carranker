<?php

declare(strict_types=1);

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

interface IBaseRepository
{
    public function all(): Collection;

//    public function find(int $id): ?Model
//    {
//        return $this->modelClassName::find($id);
//    }
//
//    public function get(int $id): Model
//    {
//        return $this->modelClassName::findOrFail($id);
//    }
//
//    public function create(array $createArray): Model
//    {
//        $model = new $this->modelClassName($createArray);
//        $model->save();
//
//        return $model;
//    }
//
//    public function update(Model $model): void
//    {
//        if (get_class($model) === $this->modelClassName) {
//            $model->save();
//        } else {
//            throw new \Exception("Invalid model passed to repository.");
//        }
//    }

    public function delete(int $id): void;
}
