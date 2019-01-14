<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Repositories\MakeRepository;
use Illuminate\Routing\Controller as BaseController;

class ModelnamesController extends BaseController
{
    public function getModelNames(string $makename)
    {
        $makeRepository = new MakeRepository();
        $models = $makeRepository->getByName($makename)->getModels();

        $modelnames = [];
        foreach($models as $model) {
            $modelnames[] = $model->getMakename() . ';' . $model->getName();
        }

        return response()->json($modelnames);
    }
}