<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Forms\NavForm;
use App\Repositories\Elastic\MakeRepository;
use App\Repositories\MenuRepository;
use Closure;
use Illuminate\Contracts\View\Factory;

class ShareWithAllViews
{
    private $viewFactory;

    public function __construct(Factory $viewFactory)
    {
        $this->viewFactory = $viewFactory;
    }

    public function handle($request, Closure $next)
    {
        $action = app('request')->route()->getAction();
        $controllerAndAction = class_basename($action['controller']);
        $controllerArray = explode('@', $controllerAndAction);
        $controller = strtolower(str_replace('Controller', '', $controllerArray[0]));

        $makeRepository = new MakeRepository();
        $menuRepository = new MenuRepository();

        $this->viewFactory->share('controller', $controller);
        $this->viewFactory->share('makenames', $makeRepository->getMakeNames());
        $this->viewFactory->share('metaKeyWords', 'car, cars, ranker, rate, rank, ranking, rating, top');
        $this->viewFactory->share('metaDescription', 'Check out the top of all cars and rate your favorite cars!');
        $this->viewFactory->share('navform', new NavForm());
        $this->viewFactory->share('menuHeader', $menuRepository->getByName('navigationHeader')->getPages()->get());
        $this->viewFactory->share('menuFooter', $menuRepository->getByName('navigationFooter')->getPages()->get());


        return $next($request);
    }
}
