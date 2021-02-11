<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Models\MySQL\Menu;
use Illuminate\Database\Eloquent\Collection;

interface MenuRepositoryInterface
{
    public function all(): Collection;

    public function get(int $id): Menu;

    public function create(array $createArray): Menu;

    public function update(Menu $menu): void;

    public function delete(int $id): void;

    public function findByName(string $fxIndex): ?Menu;
}
