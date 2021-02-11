<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Models\MySQL\Page;
use Illuminate\Database\Eloquent\Collection;

interface PageRepositoryInterface
{
    public function all(): Collection;

    public function get(int $id): Page;

    public function create(array $createArray): Page;

    public function update(Page $page): void;

    public function delete(int $id): void;

    public function findByName(string $fxIndex): ?Page;

    public function getByName(string $name): Page;
}
