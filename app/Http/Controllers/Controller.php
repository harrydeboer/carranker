<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Forms\NavForm;
use App\Repositories\MakeRepository;
use App\Repositories\MenuRepository;
use App\Repositories\ModelRepository;
use App\Repositories\PageRepository;
use App\Repositories\TrimRepository;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Session\SessionManager;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected const topLength = 10;
    protected const cacheExpire = 600;
    protected $menuRepository;
    protected $pageRepository;
    protected $makeRepository;
    protected $modelRepository;
    protected $trimRepository;
    protected $redis;
    protected $cacheString;

    public function __construct()
    {
        $this->redis = new \Redis();
        $this->redis->connect(env('REDIS_HOST'), (int)env('REDIS_PORT'));
        $this->redis->auth(env('REDIS_PASSWORD'));
        $this->redis->select((int) env('REDIS_DB'));
    }

    protected function decorator()
    {
        $session = session();

        /** Lazy loading is done when the user visits the homepage for the first time. The session gets lazyLoad false
         * so that the next time the user visits the homepage there will not be lazy loading.
         */
        $user = Auth::user();
        $this->menuRepository = new MenuRepository();
        $this->pageRepository = new PageRepository();
        $this->makeRepository = new MakeRepository();
        $this->modelRepository = new ModelRepository();
        $this->trimRepository = new TrimRepository();
        View::share('isLoggedIn', is_null($user) ? false : true);
        View::share('makenames', $this->makeRepository->getMakeNames());
        View::share('metaKeyWords', 'car, cars, ranker, rate, rank, ranking, rating, top, top ' . self::topLength);
        View::share('metaDescription', 'Check out the top ' . self::topLength .
            ' of all cars and rate your favorite cars!');
        View::share('isDevEnv', App::environment() === 'local' ? 1 : 0);
        View::share('navform', new NavForm());
        View::share('menuHeader', $this->menuRepository->getByName('navigationHeader')->getPages()->get());
        View::share('menuFooter', $this->menuRepository->getByName('navigationFooter')->getPages()->get());
        $this->shareSessionCars($session);
    }

    /** When a user goes to a make or model page the make and model are stored in the session and used to fill the
     * navigation selects for make and model.
     */
    protected function shareSessionCars(SessionManager $session)
    {
        View::share('makenameSession', $session->get('makename'));
        View::share('modelnames', $this->makeRepository->getModelNames($session->get('makename')));
        View::share('modelnameSession', $session->get('modelname'));
    }

    public function search(Request $request): Response
    {
        $this->decorator();
        $form = new NavForm($request->all());

        if ($form->validateFull($request)) {
            $data = [
                'title' => 'Search results',
                'controller' => 'base',
                'makes' => $this->makeRepository->findMakesForSearch($form->query),
                'models' => $this->modelRepository->findModelsForSearch($form->query),
                'trims' => $this->trimRepository->findTrimsForSearch($form->query),
            ];

            return response()->view('base.search', $data, 200);
        }

        $data = [
            'title' => 'Search results',
            'controller' => 'base',
            'makes' => [],
            'models' => [],
            'trims' => [],
        ];

        return response()->view('base.search', $data, 200);
    }
}
