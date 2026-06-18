<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\Contracts\AnggotaRepositoryInterface;
use App\Repositories\Eloquent\AnggotaRepository;
use App\Repositories\Contracts\AgendaRepositoryInterface;
use App\Repositories\Eloquent\AgendaRepository;
use App\Repositories\Contracts\AbsensiRepositoryInterface;
use App\Repositories\Eloquent\AbsensiRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(AnggotaRepositoryInterface::class, AnggotaRepository::class);
        $this->app->bind(AgendaRepositoryInterface::class, AgendaRepository::class);
        $this->app->bind(AbsensiRepositoryInterface::class, AbsensiRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
