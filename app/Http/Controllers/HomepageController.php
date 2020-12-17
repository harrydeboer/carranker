<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\CarSpecs;
use App\Forms\FilterTopForm;
use App\Interfaces\IPageRepository;
use App\Interfaces\IRatingRepository;
use App\Interfaces\Elastic\ITrimRepository;
use App\Models\Aspect;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class HomepageController extends Controller
{
    protected const topLength = 10;
    private const minNumVotes = 30;
    private const topSliderNumber = 10;
    private const numShowMoreLess = 10;
    private const homepageNumReviews = 3;
    private $ratingRepository;
    private $trimRepository;
    private $pageRepository;

    public function __construct(IRatingRepository $ratingRepository,
                                ITrimRepository $trimRepository,
                                IPageRepository $pageRepository)
    {
        $this->ratingRepository = $ratingRepository;
        $this->trimRepository = $trimRepository;
        $this->pageRepository = $pageRepository;
    }

    public function view(): Response
    {
        $form = new FilterTopForm();
        $minNumVotes = self::minNumVotes;
        $topTrims = $this->trimRepository->findTrimsOfTop($form, $minNumVotes,self::topLength);

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
            'filterform' => $form,
            'content' => $this->pageRepository->getByName('home')->getContent(),
        ];

        return response()->view('homepage.index', $data, 200);
    }

    public function filterTop(Request $request): Response
    {
        $form = new FilterTopForm($request->all());

        if ($form->validateFull($request)) {

            $topTrims = $this->trimRepository->findTrimsOfTop($form, (int) $form->minNumVotes, (int) $form->numberOfRows);

            $data = [
                'topLength' => count($topTrims),
                'topLengthSlider' => min(count($topTrims), self::topSliderNumber),
                'topTrims' => $topTrims,
                'minNumVotes' => (int) $form->minNumVotes,
            ];

            return response()->view('homepage.filterTop', $data, 200);
        }

        $data = [
            'topTrims' => [],
            'topLengthSlider' => 0,
            'minNumVotes' => 0,
        ];

        return response()->view('homepage.filterTop', $data, 200);
    }

    /** When a user wants to see more trims in the top the extra trims are retrieved. */
    public function showMoreTopTable(Request $request, string $numberOfRows, string $offset): Response
    {
        $form = new FilterTopForm($request->all());
        $trims = $this->trimRepository->findTrimsOfTop($form, (int) $form->minNumVotes, (int) $numberOfRows, (int) $offset);

        return response()->view('homepage.showMoreTopTable', ['trims' => $trims, 'offset' => (int) $offset], 200);
    }
}
