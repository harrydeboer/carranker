<?php

namespace App\Repositories;

use App\Models\Make;
use Illuminate\Database\Eloquent\Collection;

class MakeRepository extends BaseRepository
{
    /** The make names are retrieved and sorted on ascii value.
     * This is needed for makes with special characters in their name to be sorted properly.
     */
    public function getMakeNames(): array
    {
        $makes = Make::all();
        $makenames = [];
        $makesASCII = array();
        foreach($makes as $make) {
            $makenames[$make->getName()] = $make->getName();
            $makesASCII[] = strtolower(iconv("UTF-8", "ASCII//TRANSLIT", $make->getName()));
        }
        array_multisort($makesASCII, $makenames);

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

    /** The model names are retrieved and sorted on ascii value.
     * This is needed for models with special characters in their name to be sorted properly.
     */
    public function getModelNames(?string $makename): ?array
    {
        if (is_null($makename)) {
            return null;
        }

        $models = $this->getByName($makename)->getModels();
        $modelnames = [];
        $modelsASCII = [];
        foreach($models as $model) {
            $modelnames[] = $model->getName();
            $modelsASCII[] = strtolower(iconv("UTF-8", "ASCII//TRANSLIT", $model->getName()));
        }
        array_multisort($modelsASCII, $modelnames);

        return $modelnames;
    }
}
