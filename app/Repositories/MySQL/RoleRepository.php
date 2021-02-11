<?php

declare(strict_types=1);

namespace App\Repositories\MySQL;

use App\Models\MySQL\Role;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class RoleRepository implements RoleRepositoryInterface
{
    public function __construct(
        private Role $role,
    ) {
    }

    public function all(): Collection
    {
        return Role::all();
    }

    public function get(int $id): Role
    {
        return $this->role->findOrFail($id);
    }

    public function create(array $createArray): Role
    {
        $model = new Role($createArray);
        $model->save();

        return $model;
    }

    public function update(Role $role): void
    {
        $role->save();
    }

    public function delete(int $id): void
    {
        Role::destroy($id);
    }

    public function getByName(string $name): Role
    {
        return $this->role->where('name', $name)->first();
    }
}
