<?php

namespace App\Http\Controllers\Api;

use App\DTOs\UserDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Services\User\CreateUserService;
use App\Services\User\DeleteUserService;
use App\Services\User\FindUserService;
use App\Services\User\ListUserService;
use App\Services\User\UpdateUserService;
use Fig\Http\Message\StatusCodeInterface;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function __construct(
        private readonly CreateUserService $createService,
        private readonly FindUserService $findService,
        private readonly UpdateUserService $updateService,
        private readonly DeleteUserService $deleteService,
        private readonly ListUserService $listService
    ) {}

    /**
     * Lista todos os usuários
     */
    public function index(): JsonResponse
    {
        $users = $this->listService->execute();
        return response()->json($users);
    }

    /**
     * Cria um novo usuário
     */
    public function store(UserStoreRequest $request): JsonResponse
    {
        $dto = new UserDTO($request->validated());
        $user = $this->createService->execute($dto);

        return response()->json($user, StatusCodeInterface::STATUS_CREATED);
    }

    /**
     * Mostra um usuário específico
     */
    public function show($id): JsonResponse
    {
        $user = $this->findService->execute($id);

        return $user
            ? response()->json($user)
            : $this->notFoundResponse();
    }

    /**
     * Atualiza um usuário
     */
    public function update(UserUpdateRequest $request, $id): JsonResponse
    {
        $dto = new UserDTO($request->validated());
        $user = $this->updateService->execute($id, $dto);

        return $user
            ? response()->json($user)
            : $this->notFoundResponse();
    }

    /**
     * Remove um usuário
     */
    public function destroy($id): JsonResponse
    {
        $deleted = $this->deleteService->execute($id);

        return $deleted
            ? response()->json(['message' => 'Usuário removido com sucesso'])
            : $this->notFoundResponse();
    }

    /**
     * Resposta padrão para não encontrado
     */
    private function notFoundResponse(): JsonResponse
    {
        return response()->json(
            ['message' => 'Usuário não encontrado'],
            StatusCodeInterface::STATUS_NOT_FOUND
        );
    }
}