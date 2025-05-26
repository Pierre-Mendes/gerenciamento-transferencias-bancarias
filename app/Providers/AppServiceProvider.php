<?php

namespace App\Providers;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\UserRepository;
use App\Services\User\CreateUserService;
use App\Services\User\DeleteUserService;
use App\Services\User\FindUserService;
use App\Services\User\ListUserService;
use App\Services\User\UpdateUserService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Registro dos repositórios
        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class
        );

        // Registro dos serviços
        $this->app->bind(CreateUserService::class, function ($app) {
            return new CreateUserService(
                $app->make(UserRepositoryInterface::class)
            );
        });

        $this->app->bind(FindUserService::class, function ($app) {
            return new FindUserService(
                $app->make(UserRepositoryInterface::class)
            );
        });

        $this->app->bind(ListUserService::class, function ($app) {
            return new ListUserService(
                $app->make(UserRepositoryInterface::class)
            );
        });

        $this->app->bind(UpdateUserService::class, function ($app) {
            return new UpdateUserService(
                $app->make(UserRepositoryInterface::class)
            );
        });

        $this->app->bind(DeleteUserService::class, function ($app) {
            return new DeleteUserService(
                $app->make(UserRepositoryInterface::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Configurações de boot podem ser adicionadas aqui
    }
}
