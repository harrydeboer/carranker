<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Models\MySQL\FXRate;
use Illuminate\Database\Eloquent\Collection;

interface FXRateRepositoryInterface
{
    public function all(): Collection;

    public function get(int $id): FXRate;

    public function create(array $createArray): FXRate;

    public function update(FXRate $fXRate): void;

    public function delete(int $id): void;

    public function getByName(string $fxIndex): ?FXRate;
}
