<?php

namespace App\Http\Controllers\Api;

use App\DTOs\TransferDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\TransferStoreRequest;
use App\Services\Transfer\CreateTransferService;
use App\Services\Transfer\DeleteTransferService;
use App\Services\Transfer\FindTransferService;
use App\Services\Transfer\ListTransferService;
use App\Services\Transfer\UpdateTransferService;
use Fig\Http\Message\StatusCodeInterface;
use Illuminate\Http\JsonResponse;

class TransferController extends Controller
{
    public function __construct(
        private readonly CreateTransferService $createService,
        private readonly FindTransferService $findService,
        private readonly UpdateTransferService $updateService,
        private readonly DeleteTransferService $deleteService,
        private readonly ListTransferService $listService
    ) {}

    public function index(): JsonResponse
    {
        $transfers = $this->listService->execute();
        return response()->json($transfers);
    }

    public function store(TransferStoreRequest $request): JsonResponse
    {
        $dto = new TransferDTO($request->validated());
        $transfer = $this->createService->execute($dto);
        return response()->json($transfer, StatusCodeInterface::STATUS_CREATED);
    }

    public function show($id): JsonResponse
    {
        $transfer = $this->findService->execute($id);
        return $transfer
            ? response()->json($transfer)
            : $this->notFoundResponse();
    }

    public function update(TransferStoreRequest $request, $id): JsonResponse
    {
        $dto = new TransferDTO($request->validated());
        $transfer = $this->updateService->execute($id, $dto);
        return $transfer
            ? response()->json($transfer)
            : $this->notFoundResponse();
    }

    public function destroy($id): JsonResponse
    {
        $deleted = $this->deleteService->execute($id);
        return $deleted
            ? response()->json(['message' => 'Transferência removida com sucesso'])
            : $this->notFoundResponse();
    }

    private function notFoundResponse(): JsonResponse
    {
        return response()->json(
            ['message' => 'Transferência não encontrada'],
            StatusCodeInterface::STATUS_NOT_FOUND
        );
    }
}
