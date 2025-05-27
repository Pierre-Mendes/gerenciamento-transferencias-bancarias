<?php

namespace App\Services\User;

use App\DTOs\UserDTO;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

readonly class CreateUserService
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function execute(UserDTO $dto): UserDTO
    {
        $data = [
            'name' => $dto->name,
            'cpf' => $dto->cpf,
            'email' => $dto->email,
            'password' => Hash::make($dto->password),
        ];

        return $this->userRepository->create($data);
    }
}
