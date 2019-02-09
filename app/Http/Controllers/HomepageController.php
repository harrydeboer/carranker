<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\CarSpecs;
use App\Forms\FilterTopForm;
use App\Models\Aspect;
use App\Repositories\RatingRepository;
use App\Repositories\TrimRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class HomepageController extends BaseController
{
    private const minNumVotes = 30;
    private const topSliderNumber = 10;
    private const numShowMoreLess = 10;
    private const homepageNumReviews = 3;
    private $ratingRepository;
    protected $title = 'Car Ranker';

    public function __construct()
    {
        parent::__construct();
        $this->ratingRepository = new RatingRepository();
    }

    public function view(): Response
    {
        $session = session();
        $isLazyLoad = is_null($session->get('lazyLoad'));
        $makename = $session->get('makename');
        $modelname = $session->get('modelname');
        $cacheString = $isLazyLoad ? 'homepagelazy' . $makename . $modelname : 'homepage' . $makename . $modelname;
        $user = Auth::user();
        $cacheString = is_null($user) ? $cacheString : $cacheString . 'auth';

        if ($isLazyLoad) {
            $session->put('lazyLoad', true);
        } else {
            $session->put('lazyLoad', false);
        }

        if ($this->redis->get($cacheString) !== false && is_null($session->get('aspects'))) {

            return response($this->redis->get($cacheString), 200);
        }

        $minNumVotes = $session->get('minNumVotes') ?? self::minNumVotes;
        $topTrims = $this->trimRepository->findTrimsOfTop($session, $minNumVotes,
            $session->get('numberOfRows') ?? self::topLength);

        $data = [
            'topLengthSlider' => min(count($topTrims), self::topSliderNumber),
            'numShowMoreLess' => self::numShowMoreLess,
            'lazyLoad' => $isLazyLoad,
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

        $response = response()->view('homepage.index', $data, 200);

        if (is_null($session->get('aspects'))) {
            $this->redis->set($cacheString, $response->getContent(), $this->cacheExpire);
        }

        return $response;
    }


    public function filterTop(Request $request): Response
    {
        $session = session();
        $form = new FilterTopForm($request->all());
        $trimRepository = new TrimRepository();

        if ($form->validateFull($request)) {

            /** When a user filters the top the filtering is stored in the session. */
            $session->put('minNumVotes', (int) $form->minNumVotes);
            $session->put('aspects', $form->aspects);
            $session->put('specsChoice', $form->specsChoice);
            $session->put('specsRange', $form->specsRange);

            $topTrims = $trimRepository->findTrimsOfTop($session, (int) $form->minNumVotes, (int) $form->numberOfRows);
            $session->put('numberOfRows', count($topTrims));

            $data = [
                'topLength' => count($topTrims),
                'topLengthSlider' => min(count($topTrims), self::topSliderNumber),
                'topTrims' => $topTrims,
                'minNumVotes' => $session->get('minNumVotes'),
                'lazyLoad' => false,
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
    public function showMoreTopTable(string $numberOfRows, string $offset): Response
    {
        $session = session();
        $trimRepository = new TrimRepository();
        $minNumVotes = $session->get('minNumVotes') ?? self::minNumVotes;
        $trims = $trimRepository->findTrimsOfTop($session, $minNumVotes, (int) $numberOfRows, (int) $offset);
        $session->put('numberOfRows', count($trims) + (int) $offset);

        return response()->view('homepage.showMoreTopTable', ['trims' => $trims, 'offset' => (int) $offset], 200);
    }
}