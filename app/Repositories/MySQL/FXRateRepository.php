<?php

declare(strict_types=1);

namespace App\Repositories\MySQL;

use App\Models\MySQL\FXRate;
use App\Repositories\Interfaces\FXRateRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class FXRateRepository implements FXRateRepositoryInterface
{
    public function __construct(
        private FXRate $fXRate,
    ) {
    }

    public function all(): Collection
    {
        return FXRate::all();
    }

    public function get(int $id): FXRate
    {
        return $this->fXRate->findOrFail($id);
    }

    public function create(array $createArray): FXRate
    {
        $model = new FXRate($createArray);
        $model->save();

        return $model;
    }

    public function update(FXRate $fXRate): void
    {
        $fXRate->save();
    }

    public function delete(int $id): void
    {
        FXRate::destroy($id);
    }

    public function getByName(string $fxIndex): ?FXRate
    {
        return $this->fXRate->where(['name' => $fxIndex])->first();
    }
}
