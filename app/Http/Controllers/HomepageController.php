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
    private const topNumber = 10;
    private const topSliderNumber = 10;
    private const minNumVotes = 30;
    private const numShowMoreLess = 10;
    private const homepageNumReviews = 3;
    private const lengthTopTable = 10;
    private $ratingRepository;

    public function __construct()
    {
        parent::__construct();
        $this->ratingRepository = new RatingRepository();
    }

    public function view(Request $request)
    {
        $session = $request->session();
        $minVotes = $session->get('minVotes') ?? self::minNumVotes;
        $specsChoice = CarSpecs::specsChoice();
        $specsRange = CarSpecs::specsRange();
        $topTrims = $this->trimRepository->findTrimsOfTop($session, $minVotes,
            $session->get('numberOfRows') ?? self::lengthTopTable, $specsChoice, $specsRange);
        $form = new FilterTopForm($session->all());

        $data = [
            'topLength' => min(count($topTrims), self::topNumber),
            'topLengthSlider' => min(count($topTrims), self::topSliderNumber),
            'numShowMoreLess' => self::numShowMoreLess,
            'controller' => 'homepage',
            'title' => 'Car Ranker',
            'specsChoice' => $specsChoice,
            'specsRange' => $specsRange,
            'aspects' => Aspect::getAspects(),
            'reviews' => $this->ratingRepository->findRecentReviews(self::homepageNumReviews),
            'topTrims' => $topTrims,
            'minVotes' => $minVotes,
            'session' => $session,
            'sessionStarted' => $session->get(Aspect::getAspects()[0]) === null ? 0 : 1,
            'filterform' => $form,
            'content' => $this->pageRepository->getByName('home')->getContent(),
        ];

        return View::make('homepage.index')->with($data);
    }

    public function filterTop(Request $request)
    {
        $session = $request->session();

        $form = new FilterTopForm($request->all());

        if ($form->validateFull($request)) {

            $specsChoice = CarSpecs::specsChoice();
            $specsRange = CarSpecs::specsRange();
            foreach ($specsChoice as $key => $spec) {
                $session->remove('checkAll' . $key);
                foreach ($spec['choices'] as $keyChoice => $item) {
                    $session->forget($key . $keyChoice);
                }
            }

            $session->put('minVotes', $form->minVotes);
            $array = [];
            foreach ($form->aspects as $key => $value) {
                $array[$key] = $value;
            }
            $session->put('aspects', $array);
            $array = [];
            foreach ($form->specsChoice as $key => $value) {
                $array[$key] = $value ? true : false;
            }
            $session->put('specsChoice', $array);
            $array = [];
            foreach ($form->specsRange as $key => $value) {
                $array[$key] = $value;
            }
            $session->put('specsRange', $array);

            $topTrims = $this->trimRepository->findTrimsOfTop($session, $session->get('minVotes'),
                $form->numberOfRows, $specsChoice, $specsRange);

            if (count($topTrims) < $session->get('numberOfRows')) {
                $session->put('numberOfRows', count($topTrims));
            }

            $data = [
                'topLength' => min(count($topTrims), $session->get('numberOfRows') ?? self::topNumber),
                'topLengthSlider' => min(count($topTrims), self::topSliderNumber),
                'topTrims' => $topTrims,
                'minVotes' => $session->get('minVotes'),
            ];

            return View::make('homepage.filterTop')->with($data);
        }

        $data = [
            'topTrims' => [],
            'topLengthSlider' => 0,
            'minVotes' => 0,
        ];

        return View::make('homepage.filterTop')->with($data);
    }

    public function showMoreTopTable($numberOfRows, $offset, Request $request)
    {
        $session = $request->session();

        $specsChoice = CarSpecs::specsChoice();
        $specsRange = CarSpecs::specsRange();
        $session->put('numberOfRows', $numberOfRows);

        $trims = $this->trimRepository->findTrimsOfTop($session, $session->get('minVotes') ?? 0,
            (int) $numberOfRows, $specsChoice, $specsRange, $offset);

        return View::make('homepage.showMoreTopTable')->with(['trims' => $trims, 'offset' => $offset]);
    }
}