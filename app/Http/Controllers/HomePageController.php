<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Parameters\CarSpecs;
use App\Models\Traits\AspectsTrait;
use App\Repositories\Interfaces\PageRepositoryInterface;
use App\Repositories\Interfaces\RatingRepositoryInterface;
use App\Repositories\Interfaces\TrimReadRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class HomePageController extends Controller
{
    protected const TOP_LENGTH = 10;
    private const MIN_NUM_VOTES = 30;
    private const TOP_SLIDER_NUMBER = 10;
    private const NUM_SHOW_MORE_LESS = 10;
    private const HOME_PAGE_NUM_REVIEWS = 3;

    public function __construct(
        private RatingRepositoryInterface $ratingRepository,
        private TrimReadRepositoryInterface $trimRepository,
        private PageRepositoryInterface $pageRepository,
    ) {
    }

    public function view(): Response
    {
        $minNumVotes = self::MIN_NUM_VOTES;
        $topTrims = $this->trimRepository->findTrimsOfTop([], $minNumVotes,self::TOP_LENGTH);

        $viewData = [
            'title' => 'Car Ranker',
            'topLengthSlider' => min(count($topTrims), self::TOP_SLIDER_NUMBER),
            'numShowMoreLess' => self::NUM_SHOW_MORE_LESS,
            'specsChoice' => CarSpecs::specsChoice(),
            'specsRange' => CarSpecs::specsRange(),
            'aspects' => AspectsTrait::getAspects(),
            'reviews' => $this->ratingRepository->findRecentReviews(self::HOME_PAGE_NUM_REVIEWS),
            'topTrims' => $topTrims,
            'minNumVotes' => $minNumVotes,
            'minNumVotesDefault' => self::MIN_NUM_VOTES,
            'content' => $this->pageRepository->findByName('home')?->getContent(),
        ];

        return response()->view('homePage.index', $viewData);
    }

    public function filterTop(Request $request): Response
    {
        $formData = $request->validate($this->rules());

        $topTrims = $this->trimRepository->findTrimsOfTop(
            $formData,
            (int) $formData['min-num-votes'],
            (int) $formData['number-of-rows'],
        );

        $viewData = [
            'topLength' => count($topTrims),
            'topLengthSlider' => min(count($topTrims), self::TOP_SLIDER_NUMBER),
            'topTrims' => $topTrims,
            'minNumVotes' => (int) $formData['min-num-votes'],
        ];

        return response()->view('homePage.filterTop', $viewData);
    }

    public function showMoreTopTable(Request $request): Response
    {
        $formData = $request->validate($this->rules());

        $trims = $this->trimRepository->findTrimsOfTop(
            $formData,
            (int) $formData['min-num-votes'],
            (int) $formData['number-of-rows'],
            (int) $formData['offset'],
        );

        return response()->view('homePage.showMoreTopTable', [
            'trims' => $trims,
            'offset' => (int) $formData['offset'],
        ]);
    }

    private function rules(): array
    {
        return [
            'min-num-votes' => 'required|integer',
            'aspects.*' => 'required|integer',
            'specs-choice.*' => 'accepted|nullable',
            'specs-range.*' => 'numeric|nullable',
            'number-of-rows' => 'numeric|nullable',
            'offset' => 'numeric|nullable'
        ];
    }
}
