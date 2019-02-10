<?php

declare(strict_types=1);

namespace App\Services;

use App\Forms\NavForm;
use App\Repositories\MakeRepository;
use App\Repositories\MenuRepository;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Session\SessionManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CacheService
{
    public function cacheHeader(string $controller, Request $request, \Redis $redis, int $cacheExpire): string
    {
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
        if ($redis->get($cacheString) !== false) {
            $header = response($redis->get($cacheString), 200)->getContent();
        } else {
            $this->headerDecorator($user, $session);
            $header = response()->view('header', [
                'controller' => $controller
            ], 200)->getContent();
            $redis->set($cacheString, $header, $cacheExpire);
        }

        $header = str_replace('[*metacsrf*]', '<meta name="csrf-token" content="' . csrf_token() . '" />', $header);

        return $header . '<input type="hidden" value="' . $modelname . '" id="modelnameSession">';
    }

    public function cacheFooter(string $controller, SessionManager $session, \Redis $redis, int $cacheExpire): string
    {
        $cacheString = 'footer' . $controller;

        if ($controller === 'homepage') {
            $isLazyLoad = $session->get('lazyLoad') ?? false;
        } else {
            $isLazyLoad = false;
        }

        $cacheString = $isLazyLoad ? $cacheString . 'lazy' : $cacheString;
        if ($redis->get($cacheString) !== false) {
            $footer = response($redis->get($cacheString), 200)->getContent();
        } else {
            $menuRepository = new MenuRepository();
            View::share('menuFooter', $menuRepository->getByName('navigationFooter')->getPages()->get());
            $footer = response()->view('footer', [
                'controller' => $controller,
                'lazyLoad' => $isLazyLoad,
            ], 200)->getContent();
            $redis->set($cacheString, $footer, $cacheExpire);
        }

        return $footer;
    }

    public function makeResponse(string $header, Response $response, int $responseCode, string $footer, string $title): Response
    {
        $header = str_replace('[*title*]', $title, $header);

        $page = $header . $response->getContent() . $footer;

        if ($responseCode === 200 || $responseCode === 404 || ($responseCode === 500 && env('APP_ENV') !== 'local')) {
            return response($page, $responseCode);
        }

        return $response;
    }

    protected function headerDecorator(?Authenticatable $user, SessionManager $session)
    {
        $makeRepository = new MakeRepository();
        $menuRepository = new MenuRepository();
        View::share('isLoggedIn', is_null($user) ? false : true);
        View::share('makenames', $makeRepository->getMakeNames());
        View::share('metaKeyWords', 'car, cars, ranker, rate, rank, ranking, rating, top');
        View::share('metaDescription', 'Check out the top of all cars and rate your favorite cars!');
        View::share('navform', new NavForm());
        View::share('menuHeader', $menuRepository->getByName('navigationHeader')->getPages()->get());
        View::share('makenameSession', $session->get('makename'));
        View::share('modelnames', $makeRepository->getModelNames($session->get('makename')));
        View::share('modelnameSession', $session->get('modelname'));
    }
}