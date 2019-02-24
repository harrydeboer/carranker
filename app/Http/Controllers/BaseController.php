<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\CacheService;
use Illuminate\Http\Request;
use App\Forms\NavForm;
use App\Repositories\MakeRepository;
use App\Repositories\ModelRepository;
use App\Repositories\PageRepository;
use App\Repositories\TrimRepository;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Contracts\Auth\Guard;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BaseController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected const topLength = 10;
    protected $title;
    protected $cacheExpire;
    protected $redis;
    protected $cacheString;
    protected $pageRepository;
    protected $makeRepository;
    protected $modelRepository;
    protected $trimRepository;
    protected $guard;

    public function __construct(Guard $guard)
    {
        $this->guard = $guard;
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

            if ($request->ajax()) {

                return $next($request);
            }

            $cacheService = new CacheService($this->redis, $this->cacheExpire);

            $isLoggedIn = is_null($this->guard->user()) ? false : true;
            $header = $cacheService->cacheHeader($controller, $request->route()->parameters(), $isLoggedIn);

            $response = $next($request);
            $responseCode = $response->status();

            $footer = $cacheService->cacheFooter($controller, session(), $responseCode);

            return $cacheService->makeResponse($header, $response, $responseCode, $footer, $this->title);
        });
    }

    public function search(Request $request): Response
    {
        $form = new NavForm($request->all());
        $this->title = 'Search results';

        $makeRepository = new MakeRepository();
        $modelRepository = new ModelRepository();
        $trimRepository = new TrimRepository();

        if ($form->validateFull($request)) {
            $data = [
                'makes' => $makeRepository->findMakesForSearch($form->query),
                'models' => $modelRepository->findModelsForSearch($form->query),
                'trims' => $trimRepository->findTrimsForSearch($form->query),
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
