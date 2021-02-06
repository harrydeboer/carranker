<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Parameters\CarSpecs;
use App\Models\MySQL\Aspects;
use App\Repositories\MySQL\ProfanityRepository;
use App\Validators\RatingValidator;
use App\Repositories\MySQL\FXRateRepository;
use App\Repositories\Elasticsearch\MakeRepository;
use App\Repositories\Elasticsearch\ModelRepository;
use App\Repositories\MySQL\RatingRepository;
use App\Repositories\Elasticsearch\TrimRepository;
use App\Repositories\MySQL\UserRepository;
use App\Services\TrimService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\Factory;

class ModelPageController extends Controller
{
    private const NUM_REVIEWS_PER_MODEL_PAGE = 10;

    public function __construct(
        private RatingRepository $ratingRepository,
        private FXRateRepository $fXRateRepository,
        private MakeRepository $makeRepository,
        private UserRepository $userRepository,
        private ProfanityRepository $profanityRepository,
        private ModelRepository $modelRepository,
        private TrimRepository $trimRepository,
        private TrimService $trimService,
        private Factory $viewFactory,
    ) {
    }

    public function view(string $makeName, string $modelName, Request $request): Response
    {
        $makeName = rawurldecode($makeName);
        $modelName = rawurldecode($modelName);
        $trimId = $request->query('trimId');
        $user = $this->getCurrentUser();

        $model = $this->modelRepository->getByMakeModelName($makeName, $modelName);
        $model->getMake();
        $trims = $model->getTrims();
        $reviews = $this->ratingRepository->getReviews($model, self::NUM_REVIEWS_PER_MODEL_PAGE);

        /** The links of the pagination get extra html classes to make them centered on the model page. */
        $links = str_replace(
            'pagination',
            'pagination pagination-sm row justify-content-center',
            $reviews->onEachSide(1)->links()->toHtml(),
        );

        $viewData = [
            'title' => $makeName . ' ' . $modelName,
            'aspects' => Aspects::getAspects(),
            'specsChoice' => CarSpecs::specsChoice(),
            'specsRange' => CarSpecs::specsRange(),
            'model' => $model,
            'trims' => $trims,
            'maxNumberCharactersReview' => RatingValidator::MAX_NUMBER_CHARACTERS_REVIEW,
            'isLoggedIn' => !is_null($user),
            'isVerified' => $user?->hasVerifiedEmail(),
            'profanities' => $this->profanityRepository->getProfanityNames(),
            'generationsSeriesTrims' => $this->trimService->getGenerationsSeriesTrims($trims),
            'selectedGeneration' => $this->trimRepository->findSelectedGeneration((int) $trimId),
            'reviews' => $reviews,
            'reCAPTCHAKey' => env('RE_CAPTCHA_KEY'),
            'links' => $links,
            'FXRate' => $this->fXRateRepository->getByName('euro/dollar')->getValue(),
            'ratings' => $this->userRepository->getRatingsModel($user, $model->getId()),
        ];

        $this->viewFactory->share('makeNameRoute', $makeName);
        $this->viewFactory->share('modelNameRoute', $modelName);
        $this->viewFactory->share('modelNames', $this->makeRepository->getModelNames($makeName));

        return response()->view('modelPage.index', $viewData);
    }
}
