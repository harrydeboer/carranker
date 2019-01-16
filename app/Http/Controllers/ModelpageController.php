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
    private const modelpageReviewsPerPage = 10;
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

    public function view($makename, $modelname, Request $request, $trimId='', $page=1)
    {
        $makename = str_replace('_', ' ', $makename);
        $modelname = str_replace('_', ' ', $modelname);
        $isThankYou = 0;
        $query = $request->query();
        if ($query) {
            if (isset($query['page'])) {
                $page = $query['page'];
            }
        }
        $fxrate = $this->fXRateRepository->getByName('euro/dollar')->getValue();
        $model = $this->modelRepository->getByMakeModelName($makename, $modelname);

        $form = new RatingForm($request->all());

        if ($form->validateFull($request, $form->reCaptchaToken)) {
            $isThankYou = 1;
            $this->rate($form, $model);
        }

        if (Auth::user()) {
            $ratings = $this->userRepository->getRatings(Auth::user(), 'model', $model->getId());
        } else {
            $ratings = null;
        }

        $trims = $model->getTrims();
        $hasTrimTypes = $this->trimService->hasTrimTypes($trims);
        $generationsSeriesTrims = $this->trimService->getGenerationsSeriesTrims($trims);

        $reviews = $this->modelRepository->getRatings($model, self::modelpageReviewsPerPage, $page);
        $maxNumberOfReview = $this->modelRepository->getRatings($model, self::modelpageReviewsPerPage);
        if ($trimId) {
            $trimFromUrl = $this->trimRepository->get((int) $trimId);
            $selectedGeneration = $trimFromUrl->getYearBegin() . '-' . $trimFromUrl->getYearEnd();
        } else {
            $selectedGeneration = false;
        }

        $data = [
            'controller' => 'modelpage',
            'title' => $makename . ' ' . $modelname,
            'numAspects' => count(Aspect::getAspects()),
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
            'isLoggedIn' => Auth::user(),
        ];

        return View::make('modelpage.index')->with($data);
    }

    public function rate($form, Model $model)
    {
        $trimId = (int)$form->trim;
        $trim = $this->trimRepository->get($trimId);
        $user = Auth::user();
        $rating = $this->userRepository->getRatings(Auth::user(), 'trim', $trimId);

        if (is_null($rating)) {
            $this->modelRepository->updateCarRating($model, $form->star);
            $this->trimRepository->updateCarRating($trim, $form->star);

            $createArray = ['user_id' => $user->getId(), 'model_id' => $model->getId(), 'trim_id' => $trim->getId(), 'time' => time()];
            if ($form->content === null) {
                $createArray['content'] = null;
            } else {
                $createArray['content'] = mb_convert_encoding($form->content, 'HTML-ENTITIES', 'ISO-8859-1');
            }
            foreach ($form->star as $key => $aspect) {
                $createArray[$key] = $aspect;
            }
            $this->ratingRepository->create($createArray);

        } else {
            $this->modelRepository->updateCarRating($model, $form->star, $rating);
            $this->trimRepository->updateCarRating($trim, $form->star, $rating);
            foreach ($form->star as $key => $aspect) {
                $rating->$key = $aspect;
            }
            if ($form->content === null) {
                $rating->setContent();
            } else {
                $rating->setContent($form->content);
            }
            $this->ratingRepository->update($rating);
        }
    }
}