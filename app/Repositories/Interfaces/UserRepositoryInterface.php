<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Models\MySQL\Rating;
use App\Models\MySQL\User;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface
{
    public function getByName(string $username): ?User;

    public function getByEmail(string $email): ?User;

    public function getRatingsTrim(?User $user, int $trimId): ?Rating;

    public function getRatingsModel(?User $user, int $modelId): ?Collection;
}
