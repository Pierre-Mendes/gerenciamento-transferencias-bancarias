<?php

namespace App\Services\User;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\DTOs\UserDTO;

readonly class FindUserService
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function execute(int $id): ?UserDTO
    {
        return $this->userRepository->find($id);
    }
}
