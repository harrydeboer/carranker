<?php

declare(strict_types=1);

namespace App\Services;

use App\Forms\NavForm;
use App\Repositories\MakeRepository;
use App\Repositories\MenuRepository;
use Illuminate\Session\SessionManager;
use Symfony\Component\HttpFoundation\Response;

class CacheService
{
    private $redis;
    private $cacheExpire;

    public function __construct(\Redis $redis, int $cacheExpire)
    {
        $this->redis = $redis;
        $this->cacheExpire = $cacheExpire;
    }

    public function cacheHeader(string $controller, array $routeParams, bool $isLoggedIn): string
    {
        $session = session();

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

        $cacheString = $isLoggedIn ? 'headerauth' . $makename . $controller : 'header' . $makename . $controller;
        if ($this->redis->get($cacheString) !== false) {
            $header = response($this->redis->get($cacheString), 200)->getContent();
        } else {

            $makeRepository = new MakeRepository();
            $menuRepository = new MenuRepository();

            $data = [
                'controller' => $controller,
                'isLoggedIn' => $isLoggedIn,
                'makenames' => $makeRepository->getMakeNames(),
                'metaKeyWords' => 'car, cars, ranker, rate, rank, ranking, rating, top',
                'metaDescription' => 'Check out the top of all cars and rate your favorite cars!',
                'navform' => new NavForm(),
                'menuHeader' => $menuRepository->getByName('navigationHeader')->getPages()->get(),
                'makenameSession' => $session->get('makename'),
                'modelnames' => $makeRepository->getModelNames($session->get('makename')),
                'modelnameSession' => $session->get('modelname'),
            ];

            $header = response()->view('header', $data, 200)->getContent();
            $this->redis->set($cacheString, $header, $this->cacheExpire);
        }

        return $header . '<input type="hidden" value="' . $modelname . '" id="modelnameSession">';
    }

    public function cacheFooter(string $controller, SessionManager $session, int $responseCode): string
    {
        $cacheString = 'footer' . $controller;

        if ($controller === 'homepage' && $responseCode !== 500) {
            $isLazyLoad = $session->get('lazyLoad') ?? false;
        } else {
            $isLazyLoad = false;
        }

        $cacheString = $isLazyLoad ? $cacheString . 'lazy' : $cacheString;
        if ($this->redis->get($cacheString) !== false) {
            $footer = response($this->redis->get($cacheString), 200)->getContent();
        } else {
            $menuRepository = new MenuRepository();

            $footer = response()->view('footer', [
                'controller' => $controller,
                'menuFooter' => $menuRepository->getByName('navigationFooter')->getPages()->get(),
                'lazyLoad' => $isLazyLoad,
            ],
                200)->getContent();
            $this->redis->set($cacheString, $footer, $this->cacheExpire);
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
}