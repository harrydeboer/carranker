<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Make;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class MakeRepository implements IRepository
{
    public function all(): Collection
    {
        return Make::all();
    }

    public function find(int $id): ?Make
    {
        return Make::find($id);
    }

    public function get(int $id): Make
    {
        return Make::findOrFail($id);
    }

    public function create(array $createArray): Make
    {
        $model = new Make($createArray);
        $model->save();

        return $model;
    }

    public function update(Model $model): void
    {
        $model->save();
    }

    public function delete(int $id): void
    {
        Make::destroy($id);
    }
}
