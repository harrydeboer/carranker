<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Models\MySQL\ElasticsearchJob;
use Illuminate\Database\Eloquent\Collection;

interface ElasticsearchJobRepositoryInterface
{
    public function all(): Collection;

    public function get(int $id): ElasticsearchJob;

    public function create(array $createArray): ElasticsearchJob;

    public function update(ElasticsearchJob $elasticsearchJob): void;

    public function delete(int $id): void;

    public function getAllMakesByAction(string $action): Collection;

    public function getAllModelsByAction(string $action): Collection;

    public function getAllTrimsByAction(string $action): Collection;

    public function truncate(): void;
}
