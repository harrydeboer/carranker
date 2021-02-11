<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Traits\AspectsTrait;
use App\Repositories\Interfaces\ModelWriteRepositoryInterface;
use App\Repositories\Interfaces\RatingRepositoryInterface;
use App\Repositories\Interfaces\TrimWriteRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ReviewController extends Controller
{
    public function __construct(
        private RatingRepositoryInterface $ratingRepository,
        private ModelWriteRepositoryInterface $modelRepository,
        private TrimWriteRepositoryInterface $trimRepository,
        private UserRepositoryInterface $userRepository,
    ) {
    }

    public function view(): Response
    {
        $reviews = $this->ratingRepository->findPendingReviews(10);

        $links = str_replace(
            'pagination',
            'pagination pagination-sm row justify-content-center',
            $reviews->onEachSide(1)->links()->toHtml(),
        );

        $viewData = [
            'title' => 'Reviews',
            'controller' => 'admin',
            'reviews' => $reviews,
            'links' => $links,
        ];

        return response()->view('admin.review.index', $viewData);
    }

    protected function redirectTo(): RedirectResponse
    {
        return redirect(route('admin.reviews'));
    }

    /**
     * When a review is approved the model and trim get a rating update.
     * This update needs the earlier rating if present. After updating the ratings the earlier rating is deleted.
     */
    public function approve(Request $request): RedirectResponse
    {
        $formData = $request->validate($this->rulesApprove());

        $id = (int) $formData['id'];
        $rating = $this->ratingRepository->get($id);

        $ratingArray = [];
        foreach (AspectsTrait::getAspects() as $aspect) {
            $ratingArray[$aspect] = $rating->getAspect($aspect);
        }

        $this->ratingRepository->approve($id);

        $earlierRating = $this->ratingRepository->findEarlierByTrimAndUser(
            $rating->getTrim()->getId(),
            $rating->getUser()->getId(),
        );

        $this->modelRepository->updateVotesAndRating($rating->getModel(), $ratingArray, $earlierRating);
        $this->trimRepository->updateVotesAndRating($rating->getTrim(), $ratingArray, $earlierRating);

        if (!is_null($earlierRating)) {
            $this->ratingRepository->delete($earlierRating->getId());
        }

        return $this->redirectTo();
    }

    public function delete(Request $request): RedirectResponse
    {
        $formData = $request->validate($this->rulesDelete());

        $this->ratingRepository->delete((int) $formData['id']);

        return $this->redirectTo();
    }

    protected function rulesApprove(): array
    {
        return ['id' => 'integer|required'];
    }

    protected function rulesDelete(): array
    {
        return ['id' => 'integer|required'];
    }
}
