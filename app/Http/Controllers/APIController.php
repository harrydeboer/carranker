<?php

declare(strict_types=1);

namespace App\Http\Controllers;

class APIController extends Controller
{
    public function viewMake($makeId)
    {
        $make = $this->makeRepository->get((int) $makeId);
        $make->setContent($make->getContent());

        return response()->json($make);
    }

    public function viewModel($modelId)
    {
        $model = $this->modelRepository->get((int) $modelId);
        $model->setContent($model->getContent());

        return response()->json($model);
    }

    public function viewTrim($trimId)
    {
        $trim = $this->trimRepository->get((int) $trimId);

        return response()->json($trim);
    }
}