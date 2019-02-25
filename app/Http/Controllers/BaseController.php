<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Repositories\MenuRepository;
use Illuminate\Http\Request;
use App\Forms\NavForm;
use App\Repositories\MakeRepository;
use App\Repositories\ModelRepository;
use App\Repositories\PageRepository;
use App\Repositories\TrimRepository;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BaseController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected const topLength = 10;
    protected $redis;
    protected $cacheString;
    protected $pageRepository;
    protected $guard;
    protected $viewFactory;

    public function __construct()
    {
        $this->guard = app('Illuminate\Contracts\Auth\Guard');
        $this->viewFactory = app('Illuminate\Contracts\View\Factory');
        $this->redis = new \Redis();
        $this->cacheExpire = env('APP_ENV') === 'local' ? 0 : 3600;
        $this->redis->connect(env('REDIS_HOST'), (int) env('REDIS_PORT'));
        $this->redis->auth(env('REDIS_PASSWORD'));
        $this->redis->select((int) config('database.redis.default.database'));
        $this->pageRepository = new PageRepository();

        $this->middleware(function (Request $request, \Closure $next): Response
        {
            $controllerArray = explode('\\', get_class($this));
            $controller = strtolower(str_replace('Controller', '', end($controllerArray)));
            $makeRepository = new MakeRepository();
            $menuRepository = new MenuRepository();

            $session = session();
            if ($controller === 'homepage') {
                $isLazyLoad = $session->get('lazyLoad') ?? false;
            } else {
                $isLazyLoad = false;
            }

            $this->viewFactory->share('isLoggedIn', is_null($this->guard->user()) ? false : true);
            $this->viewFactory->share('controller', $controller);
            $this->viewFactory->share('makenames', $makeRepository->getMakeNames());
            $this->viewFactory->share('metaKeyWords', 'car, cars, ranker, rate, rank, ranking, rating, top');
            $this->viewFactory->share('metaDescription', 'Check out the top of all cars and rate your favorite cars!');
            $this->viewFactory->share('navform', new NavForm());
            $this->viewFactory->share('menuHeader', $menuRepository->getByName('navigationHeader')->getPages()->get());
            $this->viewFactory->share('menuFooter', $menuRepository->getByName('navigationFooter')->getPages()->get());
            $this->viewFactory->share('lazyLoad', $isLazyLoad);

            return $next($request);
        });
    }

    public function search(Request $request): Response
    {
        $form = new NavForm($request->all());
        $makeRepository = new MakeRepository();
        $modelRepository = new ModelRepository();
        $trimRepository = new TrimRepository();

        if ($form->validateFull($request)) {
            $data = [
                'title' => 'Search results',
                'makes' => $makeRepository->findMakesForSearch($form->query),
                'models' => $modelRepository->findModelsForSearch($form->query),
                'trims' => $trimRepository->findTrimsForSearch($form->query),
            ];

            return response()->view('base.search', $data, 200);
        }

        $data = [
            'title' => 'Search results',
            'makes' => [],
            'models' => [],
            'trims' => [],
        ];

        return response()->view('base.search', $data, 200);
    }


}
