<?php

declare(strict_types=1);

namespace App\Models;

/**
 * A car model and a car trim can both be rated and must have these methods.
 */
interface CarInterface
{
    public function getVotes(): int;

    public function setVotes(int $votes): void;

    public function getAspect(string $aspect): ?float;

    public function setAspect(string $aspectName, float $aspect): void;

    public function save(array $options = []): bool;
}
