<?php

declare(strict_types=1);

namespace App\Repositories\MySQL;

use App\Models\MySQL\Trim;
use Illuminate\Database\Eloquent\Collection;

class TrimRepository extends CarRepository
{
    public function __construct(
        private Trim $trim,
    ) {
    }

    public function all(): Collection
    {
        return Trim::all();
    }

    public function get(int $id): Trim
    {
        return $this->trim->findOrFail($id);
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
