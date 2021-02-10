<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Models\MySQL\Trim;
use Illuminate\Database\Eloquent\Collection;

interface TrimWriteRepositoryInterface extends CarWriteInterface
{
    public function all(): Collection;

    public function get(int $id): Trim;

    public function create(array $createArray): Trim;

    public function delete(int $id): void;
}
