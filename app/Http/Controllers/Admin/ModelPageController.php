<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Repositories\MySQL\ModelRepository;
use App\Repositories\MySQL\ProfanityRepository;
use App\Repositories\MySQL\RatingRepository;
use App\Repositories\MySQL\TrimRepository;
use App\Repositories\MySQL\UserRepository;
use App\Validators\RatingValidator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ModelPageController extends Controller
{
    public function __construct(
        private ModelRepository $modelRepository,
        private TrimRepository $trimRepository,
        private UserRepository $userRepository,
        private RatingRepository $ratingRepository,
        private ProfanityRepository $profanityRepository,
    ) {
    }

    public function rateCar(Request $request): Response
    {
        $validator = new RatingValidator($request->all(), $this->profanityRepository->all());

        $formData = $validator->validate();

        $user = $this->getCurrentUser();
        $trimId = (int) $formData['trim-id'];
        $trim = $this->trimRepository->get($trimId);
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
            $this->modelRepository->updateVotesAndRating($model, $formData['star'], $earlierRating);
            $this->trimRepository->updateVotesAndRating($trim, $formData['star'], $earlierRating);
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
