<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\CarSpecs;
use App\Forms\RatingForm;
use App\Models\Aspect;
use App\Models\Model;
use App\Repositories\FXRateRepository;
use App\Repositories\ProfanityRepository;
use App\Repositories\RatingRepository;
use App\Repositories\UserRepository;
use App\Services\TrimService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ModelpageController extends BaseController
{
    private const numReviewsPerModelpage = 3;
    private $ratingRepository;
    private $fXRateRepository;
    private $trimService;
    private $profanityRepository;
    private $userRepository;
    protected $title;

    public function __construct()
    {
        parent::__construct();
        $this->profanityRepository = new ProfanityRepository();
        $this->ratingRepository = new RatingRepository();
        $this->fXRateRepository = new FXRateRepository();
        $this->trimService = new TrimService();
        $this->userRepository = new UserRepository();
    }

    public function view(string $makename, string $modelname, Request $request): Response
    {
        $makename = rawurldecode($makename);
        $modelname = rawurldecode($modelname);
        $user = Auth::user();
        $trimId = $request->query('trimId');
        $cacheString = 'modelpage' . $makename . $modelname . $trimId;
        $this->title = $makename . ' ' . $modelname;

        if ($this->redis->get($cacheString) !== false && is_null($user) && $request->getMethod() === 'GET') {
            return response($this->redis->get($cacheString), 200);
        }

        $this->decorator();
        $request->getMethod();
        $trimId = (int) $trimId;

        $model = $this->modelRepository->getByMakeModelName($makename, $modelname);
        $model->getMake();
        $form = new RatingForm($request->all());

        $isThankYou = false;
        if ($form->validateFull($request, $form->reCaptchaToken)) {
            $isThankYou = $this->rate($form, $model);
        }

        $trims = $model->getTrims();
        $reviews = $this->modelRepository->getReviews($model, self::numReviewsPerModelpage);

        /** The links of the pagination get extra html classes to make them centered on the modelpage. */
        $links = str_replace('pagination', 'pagination pagination-sm row justify-content-center',
            $reviews->onEachSide(1)->links());
        $trim = $this->trimRepository->find($trimId);

        $data = [
            'aspects' => Aspect::getAspects(),
            'specsChoice' => CarSpecs::specsChoice(),
            'specsRange' => CarSpecs::specsRange(),
            'model' => $model,
            'ratingform' => $form,
            'trims' => $trims,
            'isThankYou' => $isThankYou,
            'profanities' => $this->profanityRepository->getProfanityNames(),
            'generationsSeriesTrims' => $this->trimService->getGenerationsSeriesTrims($trims),
            'selectedGeneration' => $this->trimRepository->findSelectedGeneration($trim),
            'reviews' => $reviews,
            'reCaptchaKey' => env('reCaptchaKey'),
            'links' => $links,
            'hasTrimTypes' => $this->trimService->hasTrimTypes($trims),
            'FXRate' => $this->fXRateRepository->getByName('euro/dollar')->getValue(),
            'ratings' => $this->userRepository->getRatingsModel(Auth::user(), $model->getId()),
        ];

        $response = response()->view('modelpage.index', $data, 200);

        if (is_null($user) && $request->getMethod() === 'GET') {
            $this->redis->set($cacheString, $response->getContent(), $this->cacheExpire);
        }

        return $response;
    }

    /** When a user rates a trim this rating is stored and the model and trim ratings are updated. */
    public function rate(RatingForm $form, Model $model): bool
    {
        $trimArray = explode(';', $form->trimId);
        $trimId = (int) end($trimArray);
        $trim = $this->trimRepository->get($trimId);
        $user = Auth::user();
        if (is_null($user)) {
            return false;
        }
        $rating = $this->userRepository->getRatingsTrim($user, $trimId);
        $this->modelRepository->updateCarRating($model, $form->star, $rating);
        $this->trimRepository->updateCarRating($trim, $form->star, $rating);
        if (is_null($rating)) {
            $this->ratingRepository->createRating($user, $model, $trim, $form);
        } else {
            $this->ratingRepository->updateRating($rating, $form);
        }

        return true;
    }
}