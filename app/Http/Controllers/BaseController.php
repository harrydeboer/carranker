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
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class BaseController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected const topLength = 10;
    protected $title;
    protected $cacheExpire;
    protected $redis;
    protected $cacheString;
    protected $menuRepository;
    protected $pageRepository;
    protected $makeRepository;
    protected $modelRepository;
    protected $trimRepository;

    public function __construct()
    {
        $this->redis = new \Redis();
        $this->cacheExpire = env('APP_ENV') === 'local' ? 0 : 600;
        $this->redis->connect(env('REDIS_HOST'), (int)env('REDIS_PORT'));
        $this->redis->auth(env('REDIS_PASSWORD'));
        $this->redis->select((int) config('database.redis.default.database'));

        $this->menuRepository = new MenuRepository();
        $this->pageRepository = new PageRepository();
        $this->makeRepository = new MakeRepository();
        $this->modelRepository = new ModelRepository();
        $this->trimRepository = new TrimRepository();

        $this->middleware(function (Request $request, \Closure $next): Response
        {
            $controllerArray = explode('\\', get_class($this));
            $controller = strtolower(str_replace('Controller', '', end($controllerArray)));

            if ($request->ajax()) {
                $response = $next($request);
                $responseCode = $response->status();
                $page = $response->getContent();
            } else {
                $session = session();
                $user = Auth::user();

                $routeParams = $request->route()->parameters();
                if (isset($routeParams['make'])) {
                    $makename = rawurldecode($routeParams['make']);
                    $session->put('makename', $makename);
                    if (!isset($routeParams['model'])) {
                        $session->put('modelname', '');
                    }
                } else {
                    $makename = $session->get('makename');
                }
                if (isset($routeParams['model'])) {
                    $modelname = rawurldecode($routeParams['model']);
                    $session->put('modelname', $modelname);
                } else {
                    $modelname = $session->get('modelname');
                }

                $cacheString = is_null($user) ? 'header' . $makename . $controller : 'headerauth' . $makename . $controller;
                if ($this->redis->get($cacheString) !== false) {
                    $header = response($this->redis->get($cacheString), 200)->getContent();
                } else {
                    $this->decorator();
                    $header = response()->view('header', [
                        'controller' => $controller
                    ], 200)->getContent();
                    $this->redis->set($cacheString, $header, $this->cacheExpire);
                }

                $response = $next($request);
                $responseCode = $response->status();

                $cacheString = 'footer' . $controller;
                if ($this->redis->get($cacheString) !== false) {
                    $footer = response($this->redis->get($cacheString), 200)->getContent();
                } else {
                    $this->decorator();
                    $footer = response()->view('footer', ['controller' => $controller], 200)->getContent();
                    $this->redis->set($cacheString, $footer, $this->cacheExpire);
                }

                $header = str_replace('[*metacsrf*]', '<meta name="csrf-token" content="' . csrf_token() . '" />', $header);
                $header = str_replace('[*title*]', $this->title, $header);
                $page = $header .
                    '<input type="hidden" value="' . $modelname . '" id="modelnameSession">' .
                    $response->getContent() . $footer;
            }

            if ($responseCode === 200 || $responseCode === 404) {
                return response($page, $responseCode);
            }

            return $response;
        });
    }

    protected function decorator()
    {
        $session = session();
        $user = Auth::user();
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
        $form = new NavForm($request->all());
        $this->title = 'Search results';

        if ($form->validateFull($request)) {
            $data = [
                'makes' => $this->makeRepository->findMakesForSearch($form->query),
                'models' => $this->modelRepository->findModelsForSearch($form->query),
                'trims' => $this->trimRepository->findTrimsForSearch($form->query),
            ];

            return response()->view('base.search', $data, 200);
        }

        $data = [
            'makes' => [],
            'models' => [],
            'trims' => [],
        ];

        return response()->view('base.search', $data, 200);
    }
}
