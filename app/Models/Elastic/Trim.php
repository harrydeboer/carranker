<?php

declare(strict_types=1);

namespace App\Models\Elastic;

class Trim extends Base
{
    public function getIndexName()
    {
        return 'trims';
    }
}