<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Repositories\Elastic\MakeRepository;
use App\Repositories\MenuRepository;
use Closure;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;

class ShareWithAllViews
{
    public function __construct(
        private Factory $viewFactory,
        private MakeRepository $makeRepository,
        private MenuRepository $menuRepository,
    ) {
    }

    /**
     * All web views get common data.
     */
    public function handle(Request $request, Closure $next)
    {
        $action = $request->route()->getAction();
        $controllerAndAction = class_basename($action['controller']);
        $controllerArray = explode('@', $controllerAndAction);
        $controller = str_replace('Controller', '', $controllerArray[0]);

        $this->viewFactory->share('controller', lcfirst($controller));
        $this->viewFactory->share('makeNames', $this->makeRepository->getMakeNames());
        $this->viewFactory->share('metaKeyWords', 'car, cars, ranker, rate, rank, ranking, rating, top');
        $this->viewFactory->share('metaDescription', 'Check out the top of all cars and rate your favorite cars!');
        $this->viewFactory->share('menuHeader', $this->menuRepository
            ->findByName('menuHeader')?->getPages()->get());
        $this->viewFactory->share('menuFooter', $this->menuRepository
            ->findByName('menuFooter')?->getPages()->get());


        return $next($request);
    }
}
