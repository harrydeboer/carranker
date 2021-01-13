<?php

declare(strict_types=1);

namespace App\Models\Elastic;

trait AspectsProperties
{
    protected ?float $design;
    protected ?float $comfort;
    protected ?float $reliability;
    protected ?float $costs;
    protected ?float $performance;
}