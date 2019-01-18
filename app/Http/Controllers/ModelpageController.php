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
    private const numReviewsPerModelpage = 3;
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

    public function view(string $makename, string $modelname, Request $request, string $trimId='0'): \Illuminate\View\View
    {
        $makename = urldecode($makename);
        $modelname = urldecode($modelname);
        $session = $request->session();
        $session->put('makename', $makename);
        $session->put('modelname', $modelname);
        $this->shareSessionCars($session);

        $model = $this->modelRepository->getByMakeModelName($makename, $modelname);
        $form = new RatingForm($request->all());

        $isThankYou = 0;
        if ($form->validateFull($request, $form->reCaptchaToken)) {
            $isThankYou = 1;
            $this->rate($form, $model);
        }

        $trims = $model->getTrims();
        $reviews = $this->modelRepository->getReviews($model, self::numReviewsPerModelpage);
        $links = str_replace('pagination', 'pagination pagination-sm row justify-content-center',
            $reviews->onEachSide(1)->links());

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
            'profanities' => $this->profanityRepository->getProfanityNames(),
            'generationsSeriesTrims' => $this->trimService->getGenerationsSeriesTrims($trims),
            'selectedGeneration' => $this->trimRepository->findSelectedGeneration((int) $trimId),
            'reviews' => $reviews,
            'links' => $links,
            'hasTrimTypes' => $this->trimService->hasTrimTypes($trims),
            'FXRate' => $this->fXRateRepository->getByName('euro/dollar')->getValue(),
            'ratings' => $this->userRepository->getRatingsModel(Auth::user(), $model->getId()),
        ];

        return View::make('modelpage.index')->with($data);
    }

    public function rate(RatingForm $form, Model $model)
    {
        $trimId = (int) $form->trimId;
        $trim = $this->trimRepository->get($trimId);
        $user = Auth::user();
        $rating = $this->userRepository->getRatingsTrim($user, $trimId);
        $this->modelRepository->updateCarRating($model, $form->star, $rating);
        $this->trimRepository->updateCarRating($trim, $form->star, $rating);
        if (is_null($rating)) {
            $this->ratingRepository->createRating($user, $model, $trim, $form);
        } else {
            $this->ratingRepository->updateRating($rating, $form);
        }
    }
}