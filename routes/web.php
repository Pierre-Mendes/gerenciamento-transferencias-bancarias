<?php

use App\Http\Controllers\Api\UserController;
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
});
