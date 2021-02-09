<?php

declare(strict_types=1);

namespace App\Providers;

use App\Http\Controllers\Admin\Controller;
use App\Http\Controllers\Admin\ModelPageController;
use App\Http\Controllers\Admin\ReviewController;
use App\Repositories\Elasticsearch\MakeRepository;
use App\Repositories\Elasticsearch\ModelRepository;
use App\Repositories\Elasticsearch\TrimRepository;
use App\Repositories\Interfaces\ElasticJobRepositoryInterface;
use App\Repositories\Interfaces\FXRateRepositoryInterface;
use App\Repositories\Interfaces\MailUserRepositoryInterface;
use App\Repositories\Interfaces\MakeRepositoryInterface;
use App\Repositories\Interfaces\MenuRepositoryInterface;
use App\Repositories\Interfaces\ModelRepositoryInterface;
use App\Repositories\Interfaces\PageRepositoryInterface;
use App\Repositories\Interfaces\ProfanityRepositoryInterface;
use App\Repositories\Interfaces\RatingRepositoryInterface;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use App\Repositories\Interfaces\TrimRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\MySQL\ElasticJobRepository;
use App\Repositories\MySQL\FXRateRepository;
use App\Repositories\MySQL\MailUserRepository;
use App\Repositories\MySQL\MenuRepository;
use App\Repositories\MySQL\PageRepository;
use App\Repositories\MySQL\ProfanityRepository;
use App\Repositories\MySQL\RatingRepository;
use App\Repositories\MySQL\RoleRepository;
use App\Repositories\MySQL\UserRepository;
use Illuminate\Database\Schema\Builder;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Builder::defaultStringLength(191);
        if (env('APP_ENV') === 'acceptance' || env('APP_ENV') === 'production' ) {
            URL::forceScheme('https');
        }

        Paginator::useBootstrap();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ElasticJobRepositoryInterface::class, ElasticJobRepository::class);
        $this->app->bind(FXRateRepositoryInterface::class, FXRateRepository::class);
        $this->app->bind(MailUserRepositoryInterface::class, MailUserRepository::class);
        $this->app->bind(MakeRepositoryInterface::class, MakeRepository::class);
        $this->app->bind(MenuRepositoryInterface::class, MenuRepository::class);
        $this->app->bind(ModelRepositoryInterface::class, ModelRepository::class);
        $this->app->bind(PageRepositoryInterface::class, PageRepository::class);
        $this->app->bind(ProfanityRepositoryInterface::class, ProfanityRepository::class);
        $this->app->bind(RatingRepositoryInterface::class, RatingRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(TrimRepositoryInterface::class, TrimRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);

        $this->app->when(ModelPageController::class)->needs(ModelRepositoryInterface::class)
            ->give(\App\Repositories\MySQL\ModelRepository::class);
        $this->app->when(ModelPageController::class)->needs(TrimRepositoryInterface::class)
            ->give(\App\Repositories\MySQL\TrimRepository::class);
        $this->app->when(ReviewController::class)->needs(ModelRepositoryInterface::class)
            ->give(\App\Repositories\MySQL\ModelRepository::class);
        $this->app->when(ReviewController::class)->needs(TrimRepositoryInterface::class)
            ->give(\App\Repositories\MySQL\TrimRepository::class);
    }
}
