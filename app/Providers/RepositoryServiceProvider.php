<?php

declare(strict_types=1);

namespace App\Providers;

use App\Interfaces\IBaseRepository;
use App\Interfaces\IFXRateRepository;
use App\Interfaces\IMenuRepository;
use App\Interfaces\IPageRepository;
use App\Interfaces\IProfanityRepository;
use App\Interfaces\IRatingRepository;
use App\Interfaces\IUserRepository;
use App\Interfaces\IMakeRepository;
use App\Interfaces\IModelRepository;
use App\Interfaces\ITrimRepository;
use App\Repositories\BaseRepository;
use App\Repositories\FXRateRepository;
use App\Repositories\MakeRepository;
use App\Repositories\MenuRepository;
use App\Repositories\ModelRepository;
use App\Repositories\PageRepository;
use App\Repositories\ProfanityRepository;
use App\Repositories\RatingRepository;
use App\Repositories\TrimRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(\App\Interfaces\Elastic\IBaseRepository::class, \App\Repositories\Elastic\BaseRepository::class);
        $this->app->bind(\App\Interfaces\Elastic\IMakeRepository::class, \App\Repositories\Elastic\MakeRepository::class);
        $this->app->bind(\App\Interfaces\Elastic\IModelRepository::class, \App\Repositories\Elastic\ModelRepository::class);
        $this->app->bind(\App\Interfaces\Elastic\ITrimRepository::class, \App\Repositories\Elastic\TrimRepository::class);
        $this->app->bind(IBaseRepository::class, BaseRepository::class);
        $this->app->bind(IUserRepository::class, UserRepository::class);
        $this->app->bind(IPageRepository::class, PageRepository::class);
        $this->app->bind(IMenuRepository::class, MenuRepository::class);
        $this->app->bind(IProfanityRepository::class, ProfanityRepository::class);
        $this->app->bind(IRatingRepository::class, RatingRepository::class);
        $this->app->bind(IFXRateRepository::class, FXRateRepository::class);
        $this->app->bind(IMakeRepository::class, MakeRepository::class);
        $this->app->bind(IModelRepository::class, ModelRepository::class);
        $this->app->bind(ITrimRepository::class, TrimRepository::class);
    }
}
