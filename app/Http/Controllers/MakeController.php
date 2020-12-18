<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Repositories\Elastic\MakeRepository;
use Illuminate\Http\Response;
use Illuminate\Contracts\View\Factory;

class MakeController extends Controller
{
    private Factory $viewFactory;
    private MakeRepository $makeRepository;

    public function __construct(Factory $viewFactory, MakeRepository $makeRepository)
    {
        $this->viewFactory = $viewFactory;
        $this->makeRepository = $makeRepository;
    }

    public function view(string $makename): Response
    {
        $makename = rawurldecode($makename);
        $make = $this->makeRepository->getByName($makename);

        $models = $make->getModels();
        $data = [
            'title' => $makename,
            'make' => $make,
            'models' => $models,
        ];

        $this->viewFactory->share('makenameRoute', $makename);
        $this->viewFactory->share('modelnames', $this->makeRepository->getModelNames($makename));

        return response()->view('make.index', $data, 200);
    }
}
