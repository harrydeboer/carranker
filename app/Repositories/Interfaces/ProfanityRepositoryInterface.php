<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Models\MySQL\Profanity;
use Illuminate\Database\Eloquent\Collection;

interface ProfanityRepositoryInterface
{
    public function all(): Collection;

    public function get(int $id): Profanity;

    public function create(array $createArray): Profanity;

    public function update(Profanity $profanity): void;

    public function delete(int $id): void;

    public function getProfanityNames(): string;
}
