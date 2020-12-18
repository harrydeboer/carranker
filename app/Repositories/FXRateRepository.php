<?php

namespace App\Repositories;

use App\Models\FXRate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class FXRateRepository implements IRepository
{
    public function all(): Collection
    {
        return FXRate::all();
    }

    public function find(int $id): ?FXRate
    {
        return FXRate::find($id);
    }

    public function get(int $id): FXRate
    {
        return FXRate::findOrFail($id);
    }

    public function create(array $createArray): FXRate
    {
        $model = new FXRate($createArray);
        $model->save();

        return $model;
    }

    public function update(Model $model): void
    {
        $model->save();
    }

    public function delete(int $id): void
    {
        FXRate::destroy($id);
    }

    public function getByName(string $fxIndex): ?FXRate
    {
        return FXRate::where(['name' => $fxIndex])->first();
    }
}
