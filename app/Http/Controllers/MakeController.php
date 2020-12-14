<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Repositories\Elastic\MakeRepository;
use Illuminate\Http\Response;

class MakeController extends Controller
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

        $viewFactory = app('Illuminate\Contracts\View\Factory');
        $viewFactory->share('makenameRoute', $makename);
        $viewFactory->share('modelnames', $makeRepository->getModelNames($makename));

        return response()->view('make.index', $data, 200);
    }
}
