<?php

namespace App\Services\Account;

use App\DTOs\AccountDTO;
use App\Repositories\Contracts\AccountRepositoryInterface;

readonly class CreateAccountService
{
    public function __construct(
        private AccountRepositoryInterface $accountRepository
    ) {}

    public function execute(AccountDTO $dto): AccountDTO
    {
        $data = [
            'user_id' => $dto->user_id,
            'number' => $dto->number,
            'balance' => $dto->balance,
        ];
        return $this->accountRepository->create($data);
    }
}
