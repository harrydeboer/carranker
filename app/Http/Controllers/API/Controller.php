<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Repositories\Interfaces\MakeReadRepositoryInterface;
use App\Repositories\Interfaces\ModelReadRepositoryInterface;
use App\Repositories\Interfaces\PageRepositoryInterface;
use App\Repositories\Interfaces\TrimReadRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use App\Services\SitemapService;
use Illuminate\Http\Response;

/**
 * When Oauth authenticated a user can get any make, model or trim as json.
 */
class Controller extends BaseController
{
    public function __construct(
        private MakeReadRepositoryInterface $makeRepository,
        private ModelReadRepositoryInterface $modelRepository,
        private TrimReadRepositoryInterface $trimRepository,
        private PageRepositoryInterface $pageRepository,
    ) {
    }

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

    /**
     * When a user selects a make then the modelNames belonging to this make are retrieved.
     */
    public function getModelNames(string $makeName): JsonResponse
    {
        return response()->json($this->makeRepository->getModelNames($makeName));
    }

    public function makeSitemap(): Response
    {
        $sitemapService = new SitemapService($this->pageRepository);
        $sitemap = $sitemapService->makeSitemap(
            $this->makeRepository->getMakeNames(),
            $this->modelRepository->getModelNames(),
        );

        return response($sitemap)->header('Content-Type', 'application/xml');
    }

}
