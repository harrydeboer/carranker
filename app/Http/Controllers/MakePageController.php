<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Repositories\Elastic\MakeRepository;
use Illuminate\Http\Response;
use Illuminate\Contracts\View\Factory;

class MakePageController extends Controller
{
    public function __construct(
        private Factory $viewFactory,
        private MakeRepository $makeRepository,
    ) {
    }

    public function view(string $makeName): Response
    {
        $makeName = rawurldecode($makeName);
        $make = $this->makeRepository->getByName($makeName);
        $models = $make->getModels();

        $viewData = [
            'title' => $makeName,
            'make' => $make,
            'models' => $models,
        ];

        $this->viewFactory->share('makeNameRoute', $makeName);
        $this->viewFactory->share('modelNames', $this->makeRepository->getModelNames($makeName));

        return response()->view('makePage.index', $viewData);
    }
}
