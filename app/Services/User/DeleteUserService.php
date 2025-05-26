<?php

namespace App\Services\User;

use App\Repositories\Contracts\UserRepositoryInterface;

readonly class DeleteUserService
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function execute(int $id): bool
    {
        return $this->userRepository->delete($id);
    }
}
