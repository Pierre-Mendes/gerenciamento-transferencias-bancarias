<?php

namespace App\Services\Account;

use App\Repositories\Contracts\AccountRepositoryInterface;
use App\DTOs\AccountDTO;

readonly class FindAccountService
{
    public function __construct(
        private AccountRepositoryInterface $accountRepository
    ) {}

    public function execute(int $id): ?AccountDTO
    {
        return $this->accountRepository->find($id);
    }
}
