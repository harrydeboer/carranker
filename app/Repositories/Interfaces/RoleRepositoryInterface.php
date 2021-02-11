<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Models\MySQL\Role;
use Illuminate\Database\Eloquent\Collection;

interface RoleRepositoryInterface
{
    public function all(): Collection;

    public function get(int $id): Role;

    public function create(array $createArray): Role;

    public function update(Role $role): void;

    public function delete(int $id): void;

    public function getByName(string $name): Role;
}
