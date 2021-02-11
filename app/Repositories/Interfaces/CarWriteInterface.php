<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Models\MySQL\CarInterface;
use App\Models\MySQL\Rating;

interface CarWriteInterface
{
    public function updateVotesAndRating(CarInterface $car, array $rating, ?Rating $earlierRating): void;
}
