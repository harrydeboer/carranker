<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\CarSpecs;
use App\Forms\FilterTopForm;
use App\Models\Aspect;
use App\Repositories\RatingRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class HomepageController extends Controller
{
    private const minNumVotes = 30;
    private const topSliderNumber = 10;
    private const numShowMoreLess = 10;
    private const homepageNumReviews = 3;
    private $ratingRepository;

    public function __construct()
    {
        parent::__construct();
        $this->ratingRepository = new RatingRepository();
    }

    public function view(): \Illuminate\View\View
    {
        /** Lazy loading is done when the user visits the homepage for the first time. The session gets lazyLoad false
         * so that the next time the user visits the homepage there will not be lazy loading.
         */
        $session = session();
        $session->put('lazyLoad', false);

        $minNumVotes = $session->get('minNumVotes') ?? self::minNumVotes;
        $topTrims = $this->trimRepository->findTrimsOfTop($session, $minNumVotes,
            $session->get('numberOfRows') ?? self::topLength);

        $data = [
            'topLengthSlider' => min(count($topTrims), self::topSliderNumber),
            'numShowMoreLess' => self::numShowMoreLess,
            'controller' => 'homepage',
            'title' => 'Car Ranker',
            'specsChoice' => CarSpecs::specsChoice(),
            'specsRange' => CarSpecs::specsRange(),
            'aspects' => Aspect::getAspects(),
            'reviews' => $this->ratingRepository->findRecentReviews(self::homepageNumReviews),
            'topTrims' => $topTrims,
            'minNumVotes' => $minNumVotes,
            'minNumVotesDefault' => self::minNumVotes,
            'filterform' => new FilterTopForm($session->all()),
            'content' => $this->pageRepository->getByName('home')->getContent(),
        ];

        return View::make('homepage.index')->with($data);
    }

    public function filterTop(Request $request): \Illuminate\View\View
    {
        $session = session();
        $form = new FilterTopForm($request->all());

        if ($form->validateFull($request)) {

            /** When a user filters the top the filtering is stored in the session. */
            $session->put('minNumVotes', (int) $form->minNumVotes);
            $session->put('aspects', $form->aspects);
            $session->put('specsChoice', $form->specsChoice);
            $session->put('specsRange', $form->specsRange);

            $topTrims = $this->trimRepository->findTrimsOfTop($session, (int) $form->minNumVotes, (int) $form->numberOfRows);
            $session->put('numberOfRows', count($topTrims));

            $data = [
                'topLength' => count($topTrims),
                'topLengthSlider' => min(count($topTrims), self::topSliderNumber),
                'topTrims' => $topTrims,
                'minNumVotes' => $session->get('minNumVotes'),
            ];

            return View::make('homepage.filterTop')->with($data);
        }

        $data = [
            'topTrims' => [],
            'topLengthSlider' => 0,
            'minNumVotes' => 0,
        ];

        return View::make('homepage.filterTop')->with($data);
    }

    /** When a user wants to see more trims in the top the extra trims are retrieved. */
    public function showMoreTopTable(string $numberOfRows, string $offset): \Illuminate\View\View
    {
        $session = session();
        $minNumVotes = $session->get('minNumVotes') ?? self::minNumVotes;
        $trims = $this->trimRepository->findTrimsOfTop($session, $minNumVotes, (int) $numberOfRows, (int) $offset);
        $session->put('numberOfRows', count($trims) + (int) $offset);

        return View::make('homepage.showMoreTopTable')->with(['trims' => $trims, 'offset' => (int) $offset]);
    }
}