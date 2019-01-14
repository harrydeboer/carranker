<?php

declare(strict_types=1);

namespace App\Repositories;

class BaseRepository
{
    protected $modelClassName;

    public function __construct()
    {
        $classNameArray = explode('\\', static::class);
        $this->modelClassName = 'App\Models\\' . str_replace('Repository', '', end($classNameArray));
    }

    public function all()
    {
        return $this->modelClassName::all();
    }

    public function get(int $id)
    {
        return $this->modelClassName::findOrFail($id);
    }

    public function create(array $createArray)
    {
        $model = new $this->modelClassName();
        $model->create($createArray);

        return $model;
    }

    public function update($model)
    {
        if (get_class($model) === $this->modelClassName) {
            $model->update();
        } else {
            throw new \Exception("Invalid model passed to repository.");
        }
    }

    public function delete(int $id)
    {
        return $this->modelClassName::destroy($id);
    }
}