<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Parameters\CarSpecs;
use App\Models\Traits\AspectsTrait;
use App\Repositories\Interfaces\FXRateRepositoryInterface;
use App\Repositories\Interfaces\MakeReadRepositoryInterface;
use App\Repositories\Interfaces\ModelReadRepositoryInterface;
use App\Repositories\Interfaces\ModelWriteRepositoryInterface;
use App\Repositories\Interfaces\ProfanityRepositoryInterface;
use App\Repositories\Interfaces\RatingRepositoryInterface;
use App\Repositories\Interfaces\TrimReadRepositoryInterface;
use App\Repositories\Interfaces\TrimWriteRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Validators\RatingValidator;
use App\Services\TrimService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\Factory;

class ModelPageController extends Controller
{
    private const NUM_REVIEWS_PER_MODEL_PAGE = 10;

    public function __construct(
        private RatingRepositoryInterface $ratingRepository,
        private FXRateRepositoryInterface $fXRateRepository,
        private MakeReadRepositoryInterface $makeRepository,
        private UserRepositoryInterface $userRepository,
        private ProfanityRepositoryInterface $profanityRepository,
        private ModelReadRepositoryInterface $modelRepository,
        private TrimReadRepositoryInterface $trimRepository,
        private ModelWriteRepositoryInterface $modelWriteRepository,
        private TrimWriteRepositoryInterface $trimWriteRepository,
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

        $trimService = new TrimService();

        $viewData = [
            'title' => $makeName . ' ' . $modelName,
            'aspects' => AspectsTrait::getAspects(),
            'specsChoice' => CarSpecs::specsChoice(),
            'specsRange' => CarSpecs::specsRange(),
            'model' => $model,
            'trims' => $trims,
            'maxNumberCharactersReview' => RatingValidator::MAX_NUMBER_CHARACTERS_REVIEW,
            'isLoggedIn' => !is_null($user),
            'isVerified' => $user?->hasVerifiedEmail(),
            'profanities' => $this->profanityRepository->getProfanityNames(),
            'generationsSeriesTrims' => $trimService->getGenerationsSeriesTrims($trims),
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

    public function rateCar(Request $request): Response
    {
        $validator = new RatingValidator($request->all());

        $formData = $validator->validate();

        $user = $this->getCurrentUser();
        $trimId = (int) $formData['trim-id'];
        $trim = $this->trimWriteRepository->get($trimId);
        $model = $trim->getModel();

        /**
         * When the new rating is a review it does not update the model and trim rating, because it is pending approval.
         * When the new rating is not a review but the earlier rating is a review that is pending then the model and
         * trim rating are not updated also and the rating is updated with pending on. The new review is created when
         * the previous rating is not pending. A review that is created is always pending. When the previous rating
         * is not pending and the new rating is not a review than the rating is updated and the model and trim ratings
         * are updated.
         * Finally when there is no earlier rating and the rating is not a review the model and trim ratings are updated
         * and the rating is created.
         */
        $earlierRating = $this->userRepository->getRatingsTrim($user, $trimId);
        $isPendingEarlierRating = $earlierRating?->getPending() === 1;
        $isReviewNewRating = !is_null($formData['content']);

        if (!$isReviewNewRating && !$isPendingEarlierRating) {
            $this->modelWriteRepository->updateVotesAndRating($model, $formData['star'], $earlierRating);
            $this->trimWriteRepository->updateVotesAndRating($trim, $formData['star'], $earlierRating);
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
