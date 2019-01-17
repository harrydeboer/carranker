<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Repositories\MakeRepository;
use App\Repositories\ModelRepository;
use App\Repositories\TrimRepository;
use Illuminate\Routing\Controller as BaseController;

class APIController extends BaseController
{
    private $makeRepository;
    private $modelRepository;
    private $trimRepository;

    public function __construct()
    {
        $this->makeRepository = new MakeRepository();
        $this->modelRepository = new ModelRepository();
        $this->trimRepository = new TrimRepository();
    }

    public function viewMake(string $makeId)
    {
        $make = $this->makeRepository->get((int) $makeId);
        $make->setContent($make->getContent());

        return response()->json($make);
    }

    public function viewModel(string $modelId)
    {
        $model = $this->modelRepository->get((int) $modelId);
        $model->setContent($model->getContent());

        return response()->json($model);
    }

    public function viewTrim(string $trimId)
    {
        $trim = $this->trimRepository->get((int) $trimId);

        return response()->json($trim);
    }

    public function getModelNames(string $makename)
    {
        return $this->makeRepository->getModelnames($makename);
    }
}