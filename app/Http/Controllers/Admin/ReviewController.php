<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aspect;
use App\Repositories\ModelRepository;
use App\Repositories\RatingRepository;
use App\Repositories\TrimRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class ReviewController extends Controller
{
    public function __construct(
        private RatingRepository $ratingRepository,
        private ModelRepository $modelRepository,
        private TrimRepository $trimRepository,
        private UserRepository $userRepository,
    ){}

    public function view(): Response
    {
        $reviews = $this->ratingRepository->findPendingReviews(10);

        $links = str_replace('pagination', 'pagination pagination-sm row justify-content-center',
                             $reviews->onEachSide(1)->links()->toHtml());

        $data = [
            'title' => 'Reviews',
            'controller' => 'admin',
            'reviews' => $reviews,
            'links' => $links,
        ];

        return response()->view('admin.review.index', $data);
    }

    protected function redirectTo(): RedirectResponse
    {
        return redirect(route('admin.reviews'));
    }

    /**
     * @throws ValidationException
     */
    public function approve(Request $request): RedirectResponse
    {
        $data = $request->validate($this->rulesApprove());

        $id = (int) $data['id'];
        $rating = $this->ratingRepository->get($id);

        $ratingArray = [];
        foreach (Aspect::getAspects() as $aspect) {
            $ratingArray[$aspect] = $rating->getAspect($aspect);
        }

        $this->ratingRepository->approve($id);

        $earlierRating = $this->ratingRepository->findEarlierByTrimAndUser(
            $rating->getTrim()->getId(),
            $rating->getUser()->getId());

        $this->modelRepository->updateVotesAndRating($rating->getModel(), $ratingArray, $earlierRating);
        $this->trimRepository->updateVotesAndRating($rating->getTrim(), $ratingArray, $earlierRating);

        if (!is_null($earlierRating)) {
            $this->ratingRepository->delete($earlierRating->getId());
        }

        return $this->redirectTo();
    }

    /**
     * @throws ValidationException
     */
    public function delete(Request $request): RedirectResponse
    {
        $data = $request->validate($this->rulesDelete());

        $this->ratingRepository->delete((int) $data['id']);

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
