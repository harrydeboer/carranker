<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\CarSpecs;
use App\Models\Aspects;
use App\Repositories\PageRepository;
use App\Repositories\RatingRepository;
use App\Repositories\Elastic\TrimRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class HomePageController extends Controller
{
    protected const topLength = 10;
    private const minNumVotes = 30;
    private const topSliderNumber = 10;
    private const numShowMoreLess = 10;
    private const homepageNumReviews = 3;

    public function __construct(
        private RatingRepository $ratingRepository,
        private TrimRepository $trimRepository,
        private PageRepository $pageRepository,
    ){}

    public function view(): Response
    {
        $minNumVotes = self::minNumVotes;
        $topTrims = $this->trimRepository->findTrimsOfTop([], $minNumVotes,self::topLength);

        $viewData = [
            'title' => 'Car Ranker',
            'topLengthSlider' => min(count($topTrims), self::topSliderNumber),
            'numShowMoreLess' => self::numShowMoreLess,
            'specsChoice' => CarSpecs::specsChoice(),
            'specsRange' => CarSpecs::specsRange(),
            'aspects' => Aspects::getAspects(),
            'reviews' => $this->ratingRepository->findRecentReviews(self::homepageNumReviews),
            'topTrims' => $topTrims,
            'minNumVotes' => $minNumVotes,
            'minNumVotesDefault' => self::minNumVotes,
            'content' => $this->pageRepository->findByName('home')?->getContent(),
        ];

        return response()->view('homePage.index', $viewData);
    }

    public function filterTop(Request $request): Response
    {
        $formData = $request->validate($this->rules());

        $topTrims = $this->trimRepository->findTrimsOfTop($formData,
                                                          (int) $formData['minNumVotes'],
                                                          (int) $formData['numberOfRows']);

        $viewData = [
            'topLength' => count($topTrims),
            'topLengthSlider' => min(count($topTrims), self::topSliderNumber),
            'topTrims' => $topTrims,
            'minNumVotes' => (int) $formData['minNumVotes'],
        ];

        return response()->view('homePage.filterTop', $viewData);
    }

    public function showMoreTopTable(Request $request): Response
    {
        $formData = $request->validate($this->rules());

        $trims = $this->trimRepository->findTrimsOfTop($formData,
                                                       (int) $formData['minNumVotes'],
                                                       (int) $formData['numberOfRows'],
                                                       (int) $formData['offset']);

        return response()->view('homePage.showMoreTopTable', [
            'trims' => $trims,
            'offset' => (int) $formData['offset']],
        );
    }

    private function rules(): array
    {
        return [
            'minNumVotes' => 'required|integer',
            'aspects.*' => 'required|integer',
            'specsChoice.*' => 'accepted|nullable',
            'specsRange.*' => 'numeric|nullable',
            'numberOfRows' => 'numeric|nullable',
            'offset' => 'numeric|nullable'
        ];
    }
}
