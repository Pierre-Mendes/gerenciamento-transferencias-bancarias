<?php

namespace App\Repositories;

use App\Models\Account;
use App\DTOs\AccountDTO;
use App\Repositories\Contracts\AccountRepositoryInterface;

class AccountRepository implements AccountRepositoryInterface
{
    public function all(): array
    {
        return Account::all()->map(fn($account) => new AccountDTO($account->toArray()))->all();
    }

    public function find(int $id): ?AccountDTO
    {
        $account = Account::find($id);
        return $account ? new AccountDTO($account->toArray()) : null;
    }

    public function create(array $data): AccountDTO
    {
        $account = Account::create($data);
        return new AccountDTO($account->toArray());
    }

    public function update(int $id, array $data): ?AccountDTO
    {
        $account = Account::find($id);
        if ($account) {
            $account->update($data);
            return new AccountDTO($account->toArray());
        }
        return null;
    }

    public function delete(int $id): bool
    {
        $account = Account::find($id);
        if ($account) {
            return $account->delete();
        }
        return false;
    }
}
