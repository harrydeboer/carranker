<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Trim;
use Illuminate\Database\Eloquent\Collection;

class TrimRepository extends CarRepository
{
    public function all(): Collection
    {
        return Trim::all();
    }

    public function find(int $id): ?Trim
    {
        return Trim::find($id);
    }

    public function get(int $id): Trim
    {
        return Trim::findOrFail($id);
    }

    public function create(array $createArray): Trim
    {
        $model = new Trim($createArray);
        $model->save();

        return $model;
    }

    public function delete(int $id): void
    {
        Trim::destroy($id);
    }
}
