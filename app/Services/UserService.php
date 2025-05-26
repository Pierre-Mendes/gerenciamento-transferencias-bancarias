<?php

namespace App\Services;

use App\DTOs\UserDTO;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class UserService
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function all(): array
    {
        return $this->userRepository->all();
    }

    public function find(int $id)
    {
        return $this->userRepository->find($id);
    }

    public function create(UserDTO $dto)
    {
        $data = [
            'name' => $dto->name,
            'cpf' => $dto->cpf,
            'email' => $dto->email,
            'password' => Hash::make($dto->password),
        ];
        return $this->userRepository->create($data);
    }

    public function update(int $id, UserDTO $dto)
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

    public function delete(int $id): bool
    {
        return $this->userRepository->delete($id);
    }
}
