<?php

namespace App\Repositories;

use App\Interfaces\IFXRateRepository;
use App\Models\FXRate;

class FXRateRepository extends BaseRepository implements IFXRateRepository
{
    public function getByName(string $fxIndex): ?FXRate
    {
        return FXRate::where(['name' => $fxIndex])->first();
    }
}
