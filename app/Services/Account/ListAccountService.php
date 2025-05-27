<?php

namespace App\Services\Account;

use App\Repositories\Contracts\AccountRepositoryInterface;

readonly class ListAccountService
{
    public function __construct(
        private AccountRepositoryInterface $accountRepository
    ) {}

    public function execute(): array
    {
        return $this->accountRepository->all();
    }
}
