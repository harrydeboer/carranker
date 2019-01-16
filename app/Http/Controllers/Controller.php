<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Forms\NavForm;
use App\Repositories\MakeRepository;
use App\Repositories\MenuRepository;
use App\Repositories\ModelRepository;
use App\Repositories\PageRepository;
use App\Repositories\TrimRepository;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\ValidationException;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

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
        View::share('makenames', $this->makeRepository->getMakenames());
        View::share('metaKeyWords', 'car, cars, ranker, rate, rank, ranking, rating, top, top 20');
        View::share('metaDescription', 'Check out the top 20 of all cars and rate cars yourself.');
        View::share('isDevEnv', App::environment() === 'local' ? 1 : 0);
        View::share('navform', new NavForm());
        View::share('menuHeader', $this->menuRepository->getByName('navigationHeader')->getPages()->get());
        View::share('menuFooter', $this->menuRepository->getByName('navigationFooter')->getPages()->get());
        View::share('reCaptchaKey', env('reCaptchaKey'));

        $this->middleware(function ($request, $next)
        {
            View::share('isLoggedIn', Auth::user());

            return $next($request);
        });
    }

    public function search(Request $request)
    {
        $form = new NavForm($request->all());

        try {
            if ($form->validate($request, $form->rules())) {

                if ($form->query === null) {
                    return redirect('/');
                }

                $data = [
                    'title' => 'Search results',
                    'controller' => 'base',
                    'makes' => $this->makeRepository->findMakesForSearch($form->query),
                    'models' => $this->modelRepository->findModelsForSearch($form->query),
                    'trims' => $this->trimRepository->findTrimsForSearch($form->query),
                ];

                return View::make('base.search')->with($data);
            }
        } catch (ValidationException $exception) {
            return false;
        }

        return redirect('/');
    }
}
