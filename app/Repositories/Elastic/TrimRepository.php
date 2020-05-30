<?php

declare(strict_types=1);

namespace App\Repositories\Elastic;

use App\Models\Aspect;
use App\Models\Trim;

class TrimRepository extends BaseRepository
{
    protected $index = 'trims';
}