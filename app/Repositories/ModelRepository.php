<?php

namespace App\Repositories;

use App\Models\Model;

class ModelRepository extends BaseRepository
{
    use CarTrait;

    public function getModelnames()
    {
        $models = Model::all();
        $modelnames = [];
        foreach($models as $model) {
            $modelnames[] = $model->getMakename() . ';' . $model->getName();
        }

        return $modelnames;
    }

    public function findModelsForSearch(string $searchString)
    {
        $words = explode(' ', $searchString);

        $queryObj = Model::orderBy('name', 'asc');
        foreach ($words as $word) {
            $queryObj->orWhere('name', 'like', "%$word%");
        }

        return $queryObj->get();
    }

    public function getByMakeModelName(string $makename, string $modelname)
    {
        $result = Model::where(['make' => $makename, 'name' => $modelname])->first();

        if (is_null($result)) {
            abort(404, "The requested model does not exist.");
        }

        return $result;
    }

    public function getRatings(Model $model, $modelpageReviewsPerPage, $page=null)
    {
        return $model->getRatings($modelpageReviewsPerPage, $page);
    }
}
