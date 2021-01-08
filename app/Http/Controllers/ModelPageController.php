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

        /** The links of the pagination get extra html classes to make them centered on the modelpage. */
        $links = str_replace('pagination', 'pagination pagination-sm row justify-content-center',
                             $reviews->onEachSide(1)->links()->toHtml());

        $data = [
            'title' => $makeName . ' ' . $modelName,
            'aspects' => Aspect::getAspects(),
            'specsChoice' => CarSpecs::specsChoice(),
            'specsRange' => CarSpecs::specsRange(),
            'model' => $model,
            'trims' => $trims,
            'isLoggedIn' => !is_null($user),
            'isVerified' => $user?->hasVerifiedEmail(),
            'profanities' => $this->profanityRepository->getProfanityNames(),
            'generationsSeriesTrims' => $this->trimService->getGenerationsSeriesTrims($trims),
            'selectedGeneration' => $this->trimRepository->findSelectedGeneration((int) $trimId),
            'reviews' => $reviews,
            'reCaptchaKey' => env('reCaptchaKey'),
            'links' => $links,
            'hasTrimTypes' => $this->trimService->hasTrimTypes($trims),
            'FXRate' => $this->fXRateRepository->getByName('euro/dollar')->getValue(),
            'ratings' => $this->userRepository->getRatingsModel($user, $model->getId()),
        ];

        $viewFactory = app('Illuminate\Contracts\View\Factory');
        $viewFactory->share('makeNameRoute', $makeName);
        $viewFactory->share('modelNameRoute', $modelName);
        $viewFactory->share('modelNames', $this->makeRepository->getModelNames($makeName));

        return response()->view('modelPage.index', $data, 200);
    }

    /** When a user rates a trim this rating is stored and the model and trim ratings are updated. */
    public function rateCar(Request $request, Guard $guard): Response
    {
        $validator = new RatingValidator($this->profanityRepository->all());
        $data['success'] = 'false';

        if ($formData = $validator->validate($request)) {

            $user = $guard->user();
            $trimId = (int) $formData['trimId'];
            $trim = $this->trimRepositoryEloquent->get($trimId);
            $model = $trim->getModel();

            $earlier = $this->userRepository->getRatingsTrim($user, $trimId);
            $pending = $earlier?->getPending() === 0;

            if (is_null($formData['content']) && !$pending) {
                $this->modelRepositoryEloquent->updateVotesAndRating($model, $formData['star'], $earlier);
                $this->trimRepositoryEloquent->updateVotesAndRating($trim, $formData['star'], $earlier);
            }

            if ($pending) {
                $this->ratingRepository->updateRating($earlier, $formData, 1);
            } elseif(is_null($earlier) || !is_null($formData['content'])) {
                $this->ratingRepository->createRating($user, $model, $trim, $formData, 1);
            } else {
                $this->ratingRepository->updateRating($earlier, $formData, 0);
            }

            $data['success'] = 'true';
        }

        return response()->view('modelPage.rateCar', $data, 200);
    }
}
