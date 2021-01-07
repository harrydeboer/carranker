<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\CarSpecs;
use App\Validators\FilterTopValidator;
use App\Models\Aspect;
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
        private PageRepository $pageRepository)
    {

    }

    public function view(): Response
    {
        $filterForm = new FilterTopValidator();
        $minNumVotes = self::minNumVotes;
        $topTrims = $this->trimRepository->findTrimsOfTop($filterForm, $minNumVotes,self::topLength);

        $data = [
            'title' => 'Car Ranker',
            'topLengthSlider' => min(count($topTrims), self::topSliderNumber),
            'numShowMoreLess' => self::numShowMoreLess,
            'specsChoice' => CarSpecs::specsChoice(),
            'specsRange' => CarSpecs::specsRange(),
            'aspects' => Aspect::getAspects(),
            'reviews' => $this->ratingRepository->findRecentReviews(self::homepageNumReviews),
            'topTrims' => $topTrims,
            'minNumVotes' => $minNumVotes,
            'minNumVotesDefault' => self::minNumVotes,
            'filterForm' => $filterForm,
            'content' => $this->pageRepository->findByName('home')?->getContent(),
        ];

        return response()->view('homePage.index', $data);
    }

    public function filterTop(Request $request): Response
    {
        $form = new FilterTopValidator($request->all());

        if ($form->validateFull($request)) {

            $topTrims = $this->trimRepository->findTrimsOfTop($form, (int) $form->minNumVotes, (int) $form->numberOfRows);

            $data = [
                'topLength' => count($topTrims),
                'topLengthSlider' => min(count($topTrims), self::topSliderNumber),
                'topTrims' => $topTrims,
                'minNumVotes' => (int) $form->minNumVotes,
            ];

            return response()->view('homePage.filterTop', $data);
        }

        $data = [
            'topTrims' => [],
            'topLengthSlider' => 0,
            'minNumVotes' => 0,
        ];

        return response()->view('homePage.filterTop', $data);
    }

    /** When a user wants to see more trims in the top the extra trims are retrieved. */
    public function showMoreTopTable(Request $request): Response
    {
        $form = new FilterTopValidator($request->all());
        $trims = $this->trimRepository->findTrimsOfTop($form,
                                                       (int) $form->minNumVotes,
                                                       (int) $form->numberOfRows,
                                                       (int) $form->offset);

        return response()->view('homePage.showMoreTopTable',
                                ['trims' => $trims, 'offset' => (int) $form->offset]);
    }
}
