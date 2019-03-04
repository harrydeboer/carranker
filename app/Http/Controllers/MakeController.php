<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Repositories\MakeRepository;
use Illuminate\Http\Response;

class MakeController extends BaseController
{
    public function view(string $makename): Response
    {
        $makename = rawurldecode($makename);
        $makeRepository = new MakeRepository();
        $make = $makeRepository->getByName($makename);

        $models = $make->getModels();
        $data = [
            'title' => $makename,
            'make' => $make,
            'models' => $models,
        ];

        $this->viewFactory->share('makenameRoute', $makename);
        $this->viewFactory->share('modelnames', $makeRepository->getModelNames($makename));

        return response()->view('make.index', $data, 200);
    }
}