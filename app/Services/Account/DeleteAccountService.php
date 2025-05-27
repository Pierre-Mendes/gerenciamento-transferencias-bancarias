<?php

namespace App\Services\Account;

use App\Repositories\Contracts\AccountRepositoryInterface;

readonly class DeleteAccountService
{
    public function __construct(
        private AccountRepositoryInterface $accountRepository
    ) {}

    public function execute(int $id): bool
    {
        return $this->accountRepository->delete($id);
    }
}
