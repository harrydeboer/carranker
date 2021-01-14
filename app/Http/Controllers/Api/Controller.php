<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Repositories\Elastic\MakeRepository;
use App\Repositories\Elastic\ModelRepository;
use App\Repositories\Elastic\TrimRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use App\Services\SitemapService;
use Illuminate\Http\Response;

/** When Oauth authenticated a user can get any make, model or trim as json. */
class Controller extends BaseController
{
    public function __construct(
        private MakeRepository $makeRepository,
        private ModelRepository $modelRepository,
        private TrimRepository $trimRepository,
        private SitemapService $sitemapService,
    ){}

    public function viewMake(string $makeId): JsonResponse
    {
        $make = $this->makeRepository->get((int) $makeId);

        return response()->json($make->getAttributes());
    }

    public function viewModel(string $modelId): JsonResponse
    {
        $model = $this->modelRepository->get((int) $modelId);

        return response()->json($model->getAttributes());
    }

    public function viewTrim(string $trimId): JsonResponse
    {
        $trim = $this->trimRepository->get((int) $trimId);

        return response()->json($trim->getAttributes());
    }

    /** When a user selects a make then the modelNames belonging to this make are retrieved. */
    public function getModelNames(string $makeName): JsonResponse
    {
        return response()->json($this->makeRepository->getModelNames($makeName));
    }

    public function makeSitemap(): Response
    {
        $sitemap = $this->sitemapService->makeSitemap($this->makeRepository->getMakeNames(),
                                                      $this->modelRepository->getModelNames());

        return response($sitemap)->header('Content-Type', 'application/xml');
    }

}
