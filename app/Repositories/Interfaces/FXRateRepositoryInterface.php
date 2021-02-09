<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Models\MySQL\FXRate;

interface FXRateRepositoryInterface
{
    public function getByName(string $fxIndex): ?FXRate;
}
