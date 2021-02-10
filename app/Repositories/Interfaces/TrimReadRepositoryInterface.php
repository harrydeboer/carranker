<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Models\Elasticsearch\Trim;

interface TrimReadRepositoryInterface extends ReadRepositoryInterface
{
    public function get(int $id): Trim;

    public function findSelectedGeneration(int $id): ?string;

    public function findTrimsOfTop(array $data, int $minNumVotes, int $lengthTopTable, int $offset=0): array;
}
