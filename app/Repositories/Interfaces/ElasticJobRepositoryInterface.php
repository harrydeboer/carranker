<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface ElasticJobRepositoryInterface
{
    public function getAllMakesByAction(string $action): Collection;

    public function getAllModelsByAction(string $action): Collection;

    public function getAllTrimsByAction(string $action): Collection;

    public function truncate(): void;
}
