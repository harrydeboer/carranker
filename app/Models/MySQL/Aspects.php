<?php

declare(strict_types=1);

namespace App\Models\MySQL;

/**
 * Rating, Model and Trim all have the same aspects. An aspect is a kind of rating a car can have.
 */
trait Aspects
{
    protected static array $aspects = ['design', 'comfort', 'reliability', 'costs', 'performance'];

    /**
     * Gets the total rating of a car if present, otherwise null is returned.
     */
    public function getRating(): ?float
    {
        $rating = 0;
        foreach (self::$aspects as $aspect) {
            if (is_null($this->$aspect)) {
                return null;
            }
            $rating += $this->$aspect;
        }

        return $rating/count(self::$aspects);
    }

    public function getAspect(string $aspect): ?float
    {
        return $this->$aspect;
    }

    public function setAspect(string $aspectName, float $aspect): void
    {
        $this->$aspectName = $aspect;
    }

    public function setAspects(array $aspects): void
    {
        foreach(self::getAspects() as $aspectName) {
            $this->$aspectName = $aspects[$aspectName];
        }
    }

    public static function getAspects(): array
    {
        return self::$aspects;
    }
}
