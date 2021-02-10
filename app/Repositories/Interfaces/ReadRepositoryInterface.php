<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface ReadRepositoryInterface
{
    public function findForSearch(string $searchString): array;

    public function all(): array;

    public function createIndex(bool $prefixTest);

    public function deleteIndex(bool $prefixTest);

    public function createAll(Collection $models): void;

    public function updateAll(Collection $models): void;

    public function deleteAll(Collection|array $models): void;
}
