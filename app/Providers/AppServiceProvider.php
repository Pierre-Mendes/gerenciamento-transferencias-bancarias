<?php

namespace App\Providers;

use App\Repositories\AccountRepository;
use App\Repositories\Contracts\AccountRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\UserRepository;
use App\Services\User\CreateUserService;
use App\Services\User\DeleteUserService;
use App\Services\User\FindUserService;
use App\Services\User\ListUserService;
use App\Services\User\UpdateUserService;
use App\Services\Account\CreateAccountService;
use App\Services\Account\FindAccountService;
use App\Services\Account\ListAccountService;
use App\Services\Account\UpdateAccountService;
use App\Services\Account\DeleteAccountService;
use App\Repositories\Contracts\TransferRepositoryInterface;
use App\Repositories\TransferRepository;
use App\Services\Transfer\CreateTransferService;
use App\Services\Transfer\FindTransferService;
use App\Services\Transfer\ListTransferService;
use App\Services\Transfer\UpdateTransferService;
use App\Services\Transfer\DeleteTransferService;
use App\Services\TransferKafkaProducer;
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

        $this->app->bind(
            AccountRepositoryInterface::class,
            AccountRepository::class
        );

        $this->app->bind(
            TransferRepositoryInterface::class,
            TransferRepository::class
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

        // Registro dos serviços de Account
        $this->app->bind(CreateAccountService::class, function ($app) {
            return new CreateAccountService(
                $app->make(AccountRepositoryInterface::class)
            );
        });
        $this->app->bind(FindAccountService::class, function ($app) {
            return new FindAccountService(
                $app->make(AccountRepositoryInterface::class)
            );
        });
        $this->app->bind(ListAccountService::class, function ($app) {
            return new ListAccountService(
                $app->make(AccountRepositoryInterface::class)
            );
        });
        $this->app->bind(UpdateAccountService::class, function ($app) {
            return new UpdateAccountService(
                $app->make(AccountRepositoryInterface::class)
            );
        });
        $this->app->bind(DeleteAccountService::class, function ($app) {
            return new DeleteAccountService(
                $app->make(AccountRepositoryInterface::class)
            );
        });

        // Registro dos serviços de Transfer
        $this->app->bind(CreateTransferService::class, function ($app) {
            return new CreateTransferService(
                $app->make(TransferRepositoryInterface::class),
                $app->make(TransferKafkaProducer::class)
            );
        });
        $this->app->bind(FindTransferService::class, function ($app) {
            return new FindTransferService(
                $app->make(TransferRepositoryInterface::class)
            );
        });
        $this->app->bind(ListTransferService::class, function ($app) {
            return new ListTransferService(
                $app->make(TransferRepositoryInterface::class)
            );
        });
        $this->app->bind(UpdateTransferService::class, function ($app) {
            return new UpdateTransferService(
                $app->make(TransferRepositoryInterface::class)
            );
        });
        $this->app->bind(DeleteTransferService::class, function ($app) {
            return new DeleteTransferService(
                $app->make(TransferRepositoryInterface::class)
            );
        });
        $this->app->bind(TransferKafkaProducer::class, function ($app) {
            return new TransferKafkaProducer();
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
