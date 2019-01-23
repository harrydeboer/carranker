<?php

namespace App\Repositories;

use App\Models\Make;
use Illuminate\Database\Eloquent\Collection;

class MakeRepository extends BaseRepository
{
    public function getMakeNames(): array
    {
        $makes = Make::all();
        $makenames = [];
        foreach($makes as $make) {
            $makenames[$make->getName()] = $make->getName();
        }

        return $makenames;
    }

    public function findMakesForSearch(string $searchString): Collection
    {
        $words = explode(' ', $searchString);

        $queryObj = Make::orderBy('name', 'asc');
        foreach ($words as $word) {
            $queryObj->orWhere('name', 'like', "%$word%");
        }

        return $queryObj->get();
    }

    public function getByName(string $name): Make
    {
        $result = Make::where('name', $name)->first();

        if (is_null($result)) {
            abort(404, "The requested make does not exist.");
        }

        return $result;
    }

    public function getModelNames(?string $makename): ?array
    {
        if (is_null($makename)) {
            return null;
        }

        $models = $this->getByName($makename)->getModels();
        $modelnames = [];
        foreach($models as $model) {
            $modelnames[] = $model->getName();
        }

        return $modelnames;
    }
}
