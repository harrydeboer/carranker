<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Models\MySQL\Menu;

interface MenuRepositoryInterface
{
    public function findByName(string $fxIndex): ?Menu;
}
