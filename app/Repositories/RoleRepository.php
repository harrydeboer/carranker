<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Collection;

class RoleRepository implements IRepository
{
    public function __construct(
        private Role $role,
    ){}

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

    public function delete(int $id): void
    {
        Role::destroy($id);
    }

    public function getByName(string $name): Role
    {
        return $this->role->where('name', $name)->first();
    }
}
