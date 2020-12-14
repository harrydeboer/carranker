<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Forms\NavForm;
use App\Repositories\Elastic\MakeRepository;
use App\Repositories\MenuRepository;
use Closure;

class ViewShare
{
    public function handle($request, Closure $next)
    {
        $guard = app('Illuminate\Contracts\Auth\Guard');
        $viewFactory = app('Illuminate\Contracts\View\Factory');

        $action = app('request')->route()->getAction();
        $controllerAndAction = class_basename($action['controller']);
        $controllerArray = explode('@', $controllerAndAction);
        $controller = strtolower(str_replace('Controller', '', $controllerArray[0]));

        $makeRepository = new MakeRepository();
        $menuRepository = new MenuRepository();

        $viewFactory->share('isLoggedIn', is_null($guard->user()) ? false : true);
        $viewFactory->share('controller', $controller);
        $viewFactory->share('makenames', $makeRepository->getMakeNames());
        $viewFactory->share('metaKeyWords', 'car, cars, ranker, rate, rank, ranking, rating, top');
        $viewFactory->share('metaDescription', 'Check out the top of all cars and rate your favorite cars!');
        $viewFactory->share('navform', new NavForm());
        $viewFactory->share('menuHeader', $menuRepository->getByName('navigationHeader')->getPages()->get());
        $viewFactory->share('menuFooter', $menuRepository->getByName('navigationFooter')->getPages()->get());


        return $next($request);
    }
}
