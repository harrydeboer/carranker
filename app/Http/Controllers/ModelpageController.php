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
use Illuminate\Contracts\Auth\Guard;

class ModelpageController extends Controller
{
    private const numReviewsPerModelpage = 1;

    public function __construct(private ProfanityRepository $profanityRepository,
                                private RatingRepository $ratingRepository,
                                private FXRateRepository $fXRateRepository,
                                private UserRepository $userRepository,
                                private MakeRepository $makeRepository,
                                private ModelRepository $modelRepository,
                                private TrimRepository $trimRepository,
                                private ModelRepositoryEloquent $modelRepositoryEloquent,
                                private TrimRepositoryEloquent $trimRepositoryEloquent,
                                private TrimService $trimService){}

    public function view(string $makename, string $modelname, Request $request, Guard $guard): Response
    {
        $makename = rawurldecode($makename);
        $modelname = rawurldecode($modelname);
        $trimId = $request->query('trimId');
        $user = $guard->user();

        $request->getMethod();

        $model = $this->modelRepository->getByMakeModelName($makename, $modelname);
        $model->getMake();
        $form = new RatingForm($this->profanityRepository);
        $trims = $model->getTrims();
        $reviews = $this->ratingRepository->getReviews($model, self::numReviewsPerModelpage);

        /** The links of the pagination get extra html classes to make them centered on the modelpage. */
        $links = str_replace('pagination', 'pagination pagination-sm row justify-content-center',
            $reviews->onEachSide(1)->links()->toHtml());

        $data = [
            'title' => $makename . ' ' . $modelname,
            'aspects' => Aspect::getAspects(),
            'specsChoice' => CarSpecs::specsChoice(),
            'specsRange' => CarSpecs::specsRange(),
            'model' => $model,
            'ratingform' => $form,
            'trims' => $trims,
            'isLoggedIn' => $user?->hasVerifiedEmail(),
            'profanities' => $this->profanityRepository->getProfanityNames(),
            'generationsSeriesTrims' => $this->trimService->getGenerationsSeriesTrims($trims),
            'selectedGeneration' => $this->trimRepository->findSelectedGeneration((int) $trimId),
            'reviews' => $reviews,
            'reCaptchaKey' => env('reCaptchaKey'),
            'links' => $links,
            'hasTrimTypes' => $this->trimService->hasTrimTypes($trims),
            'FXRate' => $this->fXRateRepository->getByName('euro/dollar')->getValue(),
            'ratings' => $this->userRepository->getRatingsModel($user, $model->getId()),
        ];

        $viewFactory = app('Illuminate\Contracts\View\Factory');
        $viewFactory->share('makenameRoute', $makename);
        $viewFactory->share('modelnameRoute', $modelname);
        $viewFactory->share('modelnames', $this->makeRepository->getModelNames($makename));

        return response()->view('modelpage.index', $data, 200);
    }

    /** When a user rates a trim this rating is stored and the model and trim ratings are updated. */
    public function ratecar(Request $request, Guard $guard): Response
    {
        $form = new RatingForm($this->profanityRepository, $request->all());
        $user = $guard->user();
        $data['success'] = 'false';

        if ($form->validateFull($request, $form->reCaptchaToken) && $user?->hasVerifiedEmail()) {

            $trimArray = explode(';', $form->trimId);
            $trimId = (int) end($trimArray);
            $trim = $this->trimRepositoryEloquent->get($trimId);
            $model = $trim->getModel();

            $rating = $this->userRepository->getRatingsTrim($user, $trimId);
            $this->modelRepositoryEloquent->updateVotesAndRating($model, $form->star, $rating);
            $this->trimRepositoryEloquent->updateVotesAndRating($trim, $form->star, $rating);
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
