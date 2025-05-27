<?php

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\TransferController;
use Fig\Http\Message\StatusCodeInterface;
use Illuminate\Support\Facades\Route;

Route::get('/', static function () {
    return view('welcome');
});

Route::middleware(['api'])->prefix('api')->group(function () {
    Route::get('/', static function () {
        return response()->json([
            'status' => 'online',
            'service' => 'API REST',
            'version' => '1.0',
            'message' => 'Bem-vindo à API'
        ], StatusCodeInterface::STATUS_OK);
    });

    Route::apiResource('users', UserController::class)->names([
        'index' => 'api.users.index',
        'store' => 'api.users.store',
        'show' => 'api.users.show',
        'update' => 'api.users.update',
        'destroy' => 'api.users.destroy'
    ]);

    Route::apiResource('accounts', AccountController::class)->names([
        'index' => 'api.accounts.index',
        'store' => 'api.accounts.store',
        'show' => 'api.accounts.show',
        'update' => 'api.accounts.update',
        'destroy' => 'api.accounts.destroy'
    ]);

    Route::apiResource('transfers', TransferController::class)->names([
        'index' => 'api.transfers.index',
        'store' => 'api.transfers.store',
        'show' => 'api.transfers.show',
        'update' => 'api.transfers.update',
        'destroy' => 'api.transfers.destroy'
    ]);
});
