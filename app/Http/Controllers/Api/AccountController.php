<?php

namespace App\Http\Controllers\Api;

use App\DTOs\AccountDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\AccountStoreRequest;
use App\Http\Requests\AccountUpdateRequest;
use App\Services\Account\CreateAccountService;
use App\Services\Account\DeleteAccountService;
use App\Services\Account\FindAccountService;
use App\Services\Account\ListAccountService;
use App\Services\Account\UpdateAccountService;
use Fig\Http\Message\StatusCodeInterface;
use Illuminate\Http\JsonResponse;

class AccountController extends Controller
{
    public function __construct(
        private readonly CreateAccountService $createService,
        private readonly FindAccountService $findService,
        private readonly UpdateAccountService $updateService,
        private readonly DeleteAccountService $deleteService,
        private readonly ListAccountService $listService
    ) {}

    public function index(): JsonResponse
    {
        $accounts = $this->listService->execute();
        return response()->json($accounts);
    }

    public function store(AccountStoreRequest $request): JsonResponse
    {
        $dto = new AccountDTO($request->validated());
        $account = $this->createService->execute($dto);
        return response()->json($account, StatusCodeInterface::STATUS_CREATED);
    }

    public function show($id): JsonResponse
    {
        $account = $this->findService->execute($id);
        return $account
            ? response()->json($account)
            : $this->notFoundResponse();
    }

    public function update(AccountUpdateRequest $request, $id): JsonResponse
    {
        $dto = new AccountDTO($request->validated());
        $account = $this->updateService->execute($id, $dto);
        return $account
            ? response()->json($account)
            : $this->notFoundResponse();
    }

    public function destroy($id): JsonResponse
    {
        $deleted = $this->deleteService->execute($id);
        return $deleted
            ? response()->json(['message' => 'Conta removida com sucesso'])
            : $this->notFoundResponse();
    }

    private function notFoundResponse(): JsonResponse
    {
        return response()->json(
            ['message' => 'Conta não encontrada'],
            StatusCodeInterface::STATUS_NOT_FOUND
        );
    }
}
