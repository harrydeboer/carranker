<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\CarSpecs;
use App\Forms\RatingForm;
use App\Models\Aspect;
use App\Repositories\FXRateRepository;
use App\Repositories\Elastic\MakeRepository;
use App\Repositories\Elastic\ModelRepository;
use App\Repositories\ModelRepository as ModelRepositoryEloquent;
use App\Repositories\ProfanityRepository;
use App\Repositories\RatingRepository;
use App\Repositories\Elastic\TrimRepository;
use App\Repositories\TrimRepository as TrimRepositoryEloquent;
use App\Repositories\UserRepository;
use App\Services\TrimService;
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
    private $makeRepository;
    private $modelRepository;
    private $trimRepository;

    public function __construct()
    {
        parent::__construct();
        $this->profanityRepository = new ProfanityRepository();
        $this->ratingRepository = new RatingRepository();
        $this->fXRateRepository = new FXRateRepository();
        $this->trimService = new TrimService();
        $this->userRepository = new UserRepository();
        $this->makeRepository = new MakeRepository();
        $this->modelRepository = new ModelRepository();
        $this->trimRepository = new TrimRepository();
    }

    public function view(string $makename, string $modelname, Request $request): Response
    {
        $makename = rawurldecode($makename);
        $modelname = rawurldecode($modelname);
        $user = $this->guard->user();
        $trimId = $request->query('trimId');
        $isThankYou = $request->query('isThankYou') === 'true';

        $request->getMethod();
        $trimId = (int) $trimId;

        $model = $this->modelRepository->getByMakeModelName($makename, $modelname);
        $model->getMake();
        $form = new RatingForm();
        $trims = $model->getTrims();

        $reviews = $this->ratingRepository->getReviews($model, self::numReviewsPerModelpage);

        /** The links of the pagination get extra html classes to make them centered on the modelpage. */
        $links = str_replace('pagination', 'pagination pagination-sm row justify-content-center',
            $reviews->onEachSide(1)->links());
        $trim = $this->trimRepository->find($trimId);

        $data = [
            'title' => $makename . ' ' . $modelname,
            'aspects' => Aspect::getAspects(),
            'specsChoice' => CarSpecs::specsChoice(),
            'specsRange' => CarSpecs::specsRange(),
            'model' => $model,
            'ratingform' => $form,
            'trims' => $trims,
            'isLoggedIn' => is_null($user) ? false : true,
            'isThankYou' => $isThankYou,
            'profanities' => $this->profanityRepository->getProfanityNames(),
            'generationsSeriesTrims' => $this->trimService->getGenerationsSeriesTrims($trims),
            'selectedGeneration' => $this->trimRepository->findSelectedGeneration($trim),
            'reviews' => $reviews,
            'reCaptchaKey' => env('reCaptchaKey'),
            'links' => $links,
            'hasTrimTypes' => $this->trimService->hasTrimTypes($trims),
            'FXRate' => $this->fXRateRepository->getByName('euro/dollar')->getValue(),
            'ratings' => $this->userRepository->getRatingsModel($this->guard->user(), $model->getId()),
        ];

        $this->viewFactory->share('makenameRoute', $makename);
        $this->viewFactory->share('modelnameRoute', $modelname);
        $this->viewFactory->share('modelnames', $this->makeRepository->getModelNames($makename));

        return response()->view('modelpage.index', $data, 200);
    }

    /** When a user rates a trim this rating is stored and the model and trim ratings are updated. */
    public function ratecar(Request $request): Response
    {
        $form = new RatingForm($request->all());
        $user = $this->guard->user();
        $data['success'] = 'false';

        if ($form->validateFull($request, $form->reCaptchaToken) && !is_null($user)) {

            $trimRepository = new TrimRepositoryEloquent();
            $modelRepository = new ModelRepositoryEloquent();
            $trimArray = explode(';', $form->trimId);
            $trimId = (int) end($trimArray);
            $trim = $trimRepository->get($trimId);
            $model = $trim->getModel();

            $rating = $this->userRepository->getRatingsTrim($user, $trimId);
            $modelRepository->updateCarRating($model, $form->star, $rating);
            $trimRepository->updateCarRating($trim, $form->star, $rating);
            if (is_null($rating)) {
                $this->ratingRepository->createRating($user, $model, $trim, $form);
            } else {
                $this->ratingRepository->updateRating($rating, $form);
            }
            $data['success'] = 'true';
        }

        return response()->view('modelpage.ratecar', $data, 200);
    }
}