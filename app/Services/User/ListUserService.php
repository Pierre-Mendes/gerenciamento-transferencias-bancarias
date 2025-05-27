<?php

namespace App\Services\User;

use App\Repositories\Contracts\UserRepositoryInterface;

readonly class ListUserService
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function execute(): array
    {
        return $this->userRepository->all();
    }
}
