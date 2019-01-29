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
    protected $menuRepository;
    protected $pageRepository;
    protected $makeRepository;
    protected $modelRepository;
    protected $trimRepository;

    public function __construct()
    {
        $this->menuRepository = new MenuRepository();
        $this->pageRepository = new PageRepository();
        $this->makeRepository = new MakeRepository();
        $this->modelRepository = new ModelRepository();
        $this->trimRepository = new TrimRepository();
        View::share('makenames', $this->makeRepository->getMakeNames());
        View::share('metaKeyWords', 'car, cars, ranker, rate, rank, ranking, rating, top, top ' . self::topLength);
        View::share('metaDescription', 'Check out the top ' . self::topLength .
            ' of all cars and rate your favorite cars!');
        View::share('isDevEnv', App::environment() === 'local' ? 1 : 0);
        View::share('navform', new NavForm());
        View::share('menuHeader', $this->menuRepository->getByName('navigationHeader')->getPages()->get());
        View::share('menuFooter', $this->menuRepository->getByName('navigationFooter')->getPages()->get());

        $this->middleware(function (Request $request, \Closure $next): Response
        {
            View::share('isLoggedIn', is_null(Auth::user()) ? false : true);
            $session = session();

            /** Lazy loading is done for the homepage if a user has not already visited the homepage. */
            $controller = explode('\\', get_class($this));
            if (end($controller) === 'HomepageController') {
                View::share('lazyLoad', $session->get('lazyLoad') ?? true);
            } else {
                View::share('lazyLoad', false);
            }
            $this->shareSessionCars($session);

            return $next($request);
        });
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

    public function search(Request $request): \Illuminate\View\View
    {
        $form = new NavForm($request->all());

        if ($form->validateFull($request)) {
            $data = [
                'title' => 'Search results',
                'controller' => 'base',
                'makes' => $this->makeRepository->findMakesForSearch($form->query),
                'models' => $this->modelRepository->findModelsForSearch($form->query),
                'trims' => $this->trimRepository->findTrimsForSearch($form->query),
            ];

            return View::make('base.search')->with($data);
        }

        $data = [
            'title' => 'Search results',
            'controller' => 'base',
            'makes' => [],
            'models' => [],
            'trims' => [],
        ];

        return View::make('base.search')->with($data);
    }
}
