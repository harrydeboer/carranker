<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Models\Elasticsearch\Model;

interface ModelReadRepositoryInterface extends ReadRepositoryInterface
{
    public function get(int $id): Model;

    public function getModelNames(): array;

    public function getByMakeModelName(string $makeName, string $modelName): Model;
}
