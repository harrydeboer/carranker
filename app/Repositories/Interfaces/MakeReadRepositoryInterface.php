<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Models\Elasticsearch\Make;

interface MakeReadRepositoryInterface extends ReadRepositoryInterface
{
    public function get(int $id): Make;

    public function getByName(string $name): Make;

    public function getMakeNames(): array;

    public function getModelNames(?string $makeName): ?array;
}
