<?php

declare(strict_types=1);

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class BaseRepository
{
    protected $modelClassName;

    public function __construct()
    {
        $classNameArray = explode('\\', static::class);
        $this->modelClassName = 'App\Models\\' . str_replace('Repository', '', end($classNameArray));
    }

    public function all(): Collection
    {
        return $this->modelClassName::all();
    }

    public function get(int $id): Model
    {
        return $this->modelClassName::findOrFail($id);
    }

    public function create(array $createArray): Model
    {
        $model = new $this->modelClassName();
        $model->create($createArray);

        return $model;
    }

    public function update(Model $model)
    {
        if (get_class($model) === $this->modelClassName) {
            $model->save();
        } else {
            throw new \Exception("Invalid model passed to repository.");
        }
    }

    public function delete(int $id)
    {
        $this->modelClassName::destroy($id);
    }
}