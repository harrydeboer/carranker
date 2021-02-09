<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

interface ProfanityRepositoryInterface
{
    public function getProfanityNames(): string;
}
