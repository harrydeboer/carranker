<?php

declare(strict_types=1);

namespace App\Models;

trait Aspect
{
    protected static $aspects = ['design', 'comfort', 'reliability', 'costs', 'performance'];

    public function getRating(): float
    {
        $rating = 0;
        foreach (self::$aspects as $aspect) {
            $rating += $this->$aspect;
        }

        return $rating/count(self::$aspects);
    }

    public function getAspect(string $aspect): float
    {
        return $this->$aspect;
    }

    public function setAspect(string $aspectName, float $aspect)
    {
        $this->$aspectName = $aspect;
    }

    public static function getAspects(): array
    {
        return self::$aspects;
    }
}