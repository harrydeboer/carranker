<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\CarSpecs;
use App\Models\Aspect;
use App\Validators\RatingValidator;
use App\Repositories\FXRateRepository;
use App\Repositories\Elastic\MakeRepository;
use App\Repositories\Elastic\ModelRepository;
use App\Repositories\ModelRepository as ModelRepositoryEloquent;
use App\Repositories\ProfanityRepository;
use App\Repositories\RatingRepository;
use App\Repositories\Elastic\TrimRepository;
use App\Repositories\TrimRepository as TrimRepositoryEloquent;
use App\Repositories\UserRepository;
use App\Services\TrimService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Contracts\Auth\Guard;

class ModelPageController extends Controller
{
    private const numReviewsPerModelPage = 10;

    public function __construct(
        private ProfanityRepository $profanityRepository,
        private RatingRepository $ratingRepository,
        private FXRateRepository $fXRateRepository,
        private UserRepository $userRepository,
        private MakeRepository $makeRepository,
        private ModelRepository $modelRepository,
        private TrimRepository $trimRepository,
        private ModelRepositoryEloquent $modelRepositoryEloquent,
        private TrimRepositoryEloquent $trimRepositoryEloquent,
        private TrimService $trimService,
    ){}

    public function view(string $makeName, string $modelName, Request $request, Guard $guard): Response
    {
        $makeName = rawurldecode($makeName);
        $modelName = rawurldecode($modelName);
        $trimId = $request->query('trimId');
        $user = $guard->user();

        $request->getMethod();

        $model = $this->modelRepository->getByMakeModelName($makeName, $modelName);
        $model->getMake();
        $trims = $model->getTrims();
        $reviews = $this->ratingRepository->getReviews($model, self::numReviewsPerModelPage);

        /** The links of the pagination get extra html classes to make them centered on the model page. */
        $links = str_replace('pagination', 'pagination pagination-sm row justify-content-center',
                             $reviews->onEachSide(1)->links()->toHtml());

        $viewData = [
            'title' => $makeName . ' ' . $modelName,
            'aspects' => Aspect::getAspects(),
            'specsChoice' => CarSpecs::specsChoice(),
            'specsRange' => CarSpecs::specsRange(),
            'model' => $model,
            'trims' => $trims,
            'maxNumberCharactersReview' => RatingValidator::maxNumberCharactersReview,
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

        $viewFactory = app('Illuminate\Contracts\View\Factory');
        $viewFactory->share('makeNameRoute', $makeName);
        $viewFactory->share('modelNameRoute', $modelName);
        $viewFactory->share('modelNames', $this->makeRepository->getModelNames($makeName));

        return response()->view('modelPage.index', $viewData);
    }

    public function rateCar(Request $request, Guard $guard): Response
    {
        $validator = new RatingValidator($request->all(), $this->profanityRepository->all());

        $formData = $validator->validate();

        $user = $guard->user();
        $trimId = (int) $formData['trimId'];
        $trim = $this->trimRepositoryEloquent->get($trimId);
        $model = $trim->getModel();

        $earlierRating = $this->userRepository->getRatingsTrim($user, $trimId);
        $isPendingEarlierRating = $earlierRating?->getPending() === 1;
        $isReviewNewRating = !is_null($formData['content']);

        if (!$isReviewNewRating && !$isPendingEarlierRating) {
            $this->modelRepositoryEloquent->updateVotesAndRating($model, $formData['star'], $earlierRating);
            $this->trimRepositoryEloquent->updateVotesAndRating($trim, $formData['star'], $earlierRating);
        }

        if ($isPendingEarlierRating) {
            $this->ratingRepository->updateRating($earlierRating, $formData, 1);
        } elseif($isReviewNewRating) {
            $this->ratingRepository->createRating($user, $model, $trim, $formData, 1);
        } elseif (is_null($earlierRating)) {
            $this->ratingRepository->createRating($user, $model, $trim, $formData, 0);
        } else {
            $this->ratingRepository->updateRating($earlierRating, $formData, 0);
        }

        return response()->view('modelPage.rateCar', ['success' => 'true']);
    }
}
