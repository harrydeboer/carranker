<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Models\MySQL\Role;

interface RoleRepositoryInterface
{
    public function getByName(string $name): Role;
}
