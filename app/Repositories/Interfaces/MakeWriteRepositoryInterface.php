<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Models\MySQL\Make;
use Illuminate\Database\Eloquent\Collection;

interface MakeWriteRepositoryInterface
{
    public function all(): Collection;

    public function get(int $id): Make;

    public function create(array $createArray): Make;

    public function update(Make $make): void;

    public function delete(int $id): void;
}
