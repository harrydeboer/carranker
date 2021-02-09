<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Models\MySQL\Page;

interface PageRepositoryInterface
{
    public function findByName(string $fxIndex): ?Page;

    public function getByName(string $name): Page;
}
