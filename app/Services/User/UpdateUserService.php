<?php

namespace App\Services\User;

use App\DTOs\UserDTO;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

readonly class UpdateUserService
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function execute(int $id, UserDTO $dto): ?UserDTO
    {
        $data = [
            'name' => $dto->name,
            'cpf' => $dto->cpf,
            'email' => $dto->email,
        ];

        if ($dto->password) {
            $data['password'] = Hash::make($dto->password);
        }

        return $this->userRepository->update($id, $data);
    }
}
