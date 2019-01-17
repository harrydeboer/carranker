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
use Illuminate\Support\Facades\View;

class ModelpageController extends Controller
{
    private const modelpageReviewsPerPage = 5;
    private $ratingRepository;
    private $fXRateRepository;
    private $trimService;
    private $profanityRepository;
    private $userRepository;

    public function __construct()
    {
        parent::__construct();
        $this->profanityRepository = new ProfanityRepository();
        $this->ratingRepository = new RatingRepository();
        $this->fXRateRepository = new FXRateRepository();
        $this->trimService = new TrimService();
        $this->userRepository = new UserRepository();
    }

    public function view(string $makename, string $modelname, Request $request, string $trimId='0')
    {
        $session = $request->session();
        $session->put('makename', $makename);
        $session->put('modelname', $modelname);
        $this->shareSessionCars($session);

        $page = $this->modelRepository->getPageNumber($request->query());
        $fxrate = $this->fXRateRepository->getByName('euro/dollar')->getValue();
        $model = $this->modelRepository->getByMakeModelName($makename, $modelname);
        $form = new RatingForm($request->all());

        if ($form->validateFull($request, $form->reCaptchaToken)) {
            $isThankYou = 1;
            $this->rate($form, $model);
        } else {
            $isThankYou = 0;
        }

        $ratings = $this->userRepository->getRatingsModel(Auth::user(), $model->getId());
        $trims = $model->getTrims();
        $hasTrimTypes = $this->trimService->hasTrimTypes($trims);
        $generationsSeriesTrims = $this->trimService->getGenerationsSeriesTrims($trims);
        $reviews = $this->modelRepository->getReviews($model, self::modelpageReviewsPerPage, $page);
        $maxNumberOfReview = $this->modelRepository->getNumOfReviews($model);
        $selectedGeneration = $this->trimRepository->findSelectedGeneration((int) $trimId);

        $data = [
            'controller' => 'modelpage',
            'title' => $makename . ' ' . $modelname,
            'aspects' => Aspect::getAspects(),
            'specsChoice' => CarSpecs::specsChoice(),
            'specsRange' => CarSpecs::specsRange(),
            'model' => $model,
            'ratingform' => $form,
            'trims' => $trims,
            'isThankYou' => $isThankYou,
            'thisPage' => $page,
            'maxPages' =>  ceil($maxNumberOfReview / self::modelpageReviewsPerPage),
            'profanities' => $this->profanityRepository->getProfanityNames(),
            'generationsSeriesTrims' => $generationsSeriesTrims,
            'selectedGeneration' => $selectedGeneration,
            'reviews' => $reviews,
            'hasTrimTypes' => $hasTrimTypes,
            'FXRate' => $fxrate,
            'ratings' => $ratings,
        ];

        return View::make('modelpage.index')->with($data);
    }

    public function rate(RatingForm $form, Model $model)
    {
        $trimId = (int) $form->trimId;
        $trim = $this->trimRepository->get($trimId);
        $user = Auth::user();
        $rating = $this->userRepository->getRatingsTrim(Auth::user(), $trimId);

        $this->modelRepository->updateCarRating($model, $form->star, $rating);
        $this->trimRepository->updateCarRating($trim, $form->star, $rating);
        if (is_null($rating)) {
            $this->ratingRepository->createRating($user, $model, $trim, $form);
        } else {
            $this->ratingRepository->updateRating($rating, $form);
        }
    }
}