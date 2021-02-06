<?php

declare(strict_types=1);

namespace App\Repositories\MySQL;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

interface IRepository
{
    public function all(): Collection;

    public function get(int $id): Model;

    public function create(array $createArray): Model;

    public function delete(int $id): void;
}
