<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Repositories\MakeRepository;
use App\Repositories\ModelRepository;
use App\Repositories\TrimRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use App\Services\SitemapService;
use Illuminate\Http\Response;

/** When Oauth authenticated a user can get any make, model or trim as json. */
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

    public function viewMake(string $makeId): JsonResponse
    {
        $make = $this->makeRepository->get((int) $makeId);
        $make->setContent($make->getContent());

        return response()->json($make);
    }

    public function viewModel(string $modelId): JsonResponse
    {
        $model = $this->modelRepository->get((int) $modelId);
        $model->setContent($model->getContent());

        return response()->json($model);
    }

    public function viewTrim(string $trimId): JsonResponse
    {
        $trim = $this->trimRepository->get((int) $trimId);

        return response()->json($trim);
    }

    /** When a user selects a make then the modelnames belonging to this make are retrieved. */
    public function getModelNames(string $makename): JsonResponse
    {
        return response()->json($this->makeRepository->getModelNames($makename));
    }

    public function makeSitemap(): Response
    {
        $sitemap = new SitemapService();
        $makeRepository = new MakeRepository();
        $modelRepository = new ModelRepository();
        $sitemap = $sitemap->makeSitemap($makeRepository->getMakeNames(), $modelRepository->getModelNames());

        return response($sitemap)->header('Content-Type', 'application/xml');
    }

}