<?php

namespace App\Repositories;

use App\Models\FXRate;

class FXRateRepository extends BaseRepository
{
    public function getByName(string $fxIndex)
    {
        return FXRate::where(['name' => $fxIndex])->first();
    }
}
