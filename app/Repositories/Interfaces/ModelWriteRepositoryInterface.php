<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Models\MySQL\Model;
use Illuminate\Database\Eloquent\Collection;

interface ModelWriteRepositoryInterface extends CarWriteInterface
{
    public function all(): Collection;

    public function get(int $id): Model;

    public function create(array $createArray): Model;

    public function delete(int $id): void;
}
