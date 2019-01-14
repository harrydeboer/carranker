<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Forms\NavForm;
use App\Repositories\MakeRepository;
use App\Repositories\MenuRepository;
use App\Repositories\ModelRepository;
use App\Repositories\PageRepository;
use App\Repositories\ProfanityRepository;
use App\Repositories\TrimRepository;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $menuRepository;
    protected $pageRepository;
    protected $profanityRepository;
    protected $makeRepository;
    protected $modelRepository;
    protected $trimRepository;
    protected $userRepository;

    public function __construct()
    {
        $this->menuRepository = new MenuRepository();
        $this->pageRepository = new PageRepository();
        $this->profanityRepository = new ProfanityRepository();
        $this->makeRepository = new MakeRepository();
        $this->modelRepository = new ModelRepository();
        $this->trimRepository = new TrimRepository();
        $this->userRepository = new UserRepository();
        View::share('makenames', $this->makeRepository->getMakenames());
        View::share('metaKeyWords', 'car, cars, ranker, rate, rank, ranking, rating, top, top 20');
        View::share('metaDescription', 'Check out the top 20 of all cars and rate cars yourself.');
        View::share('isDevEnv', App::environment() === 'local' ? 1 : 0);
        View::share('navform', new NavForm());
        View::share('menuHeader', $this->menuRepository->getByName('navigationHeader')->getPages()->get());
        View::share('menuFooter', $this->menuRepository->getByName('navigationFooter')->getPages()->get());
        View::share('reCaptchaKey', env('reCaptchaKey'));
    }

    public function navigate(Request $request)
    {
        $form = new NavForm($request->all());

        if ($form->validate($form->reCaptchaTokenNavbar, $request)) {

            if ($form->make === null && $form->model === null && $form->search === null) {
                return redirect('/');
            }

            if ($form->make && $form->model !== null && $form->search === null) {
                $modelArray = explode(';', $form->model);
                return redirect('model/' . $modelArray[0] . '/' . $modelArray[1]);
            } else if ($form->make !== null && $form->search === null) {
                return redirect('make/' . $form->make);
            }

            $data = [
                'title' => 'Search results',
                'controller' => 'base',
                'makes' => $this->makeRepository->findMakesForSearch($form->search),
                'models' => $this->modelRepository->findModelsForSearch($form->search),
                'trims' => $this->trimRepository->findTrimsForSearch($form->search),
            ];

            return View::make('base.search')->with($data);
        }

        return redirect('/');
    }
}
