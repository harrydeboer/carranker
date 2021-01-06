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

class ReviewController extends Controller
{
    public function __construct(
        private RatingRepository $ratingRepository,
        private ModelRepository $modelRepository,
        private TrimRepository $trimRepository,
        private UserRepository $userRepository,
    )
    {

    }

    public function view(): Response
    {
        $reviews = $this->ratingRepository->findPendingReviews(10);

        /** The links of the pagination get extra html classes to make them centered on the modelpage. */
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

    public function approve(Request $request): RedirectResponse
    {
        $id = (int) $request->id;
        $rating = $this->ratingRepository->get($id);

        $ratingArray = [];
        foreach (Aspect::getAspects() as $aspect) {
            $ratingArray[$aspect] = $rating->getAspect($aspect);
        }

        $this->ratingRepository->approve($id);
        $this->modelRepository->updateVotesAndRating($rating->getModel(), $ratingArray, null);
        $this->trimRepository->updateVotesAndRating($rating->getTrim(), $ratingArray, null);

        return redirect(route('admin.reviews'));
    }

    public function delete(Request $request): RedirectResponse
    {
        $this->ratingRepository->delete((int) $request->id);

        return redirect(route('admin.reviews'));
    }
}
