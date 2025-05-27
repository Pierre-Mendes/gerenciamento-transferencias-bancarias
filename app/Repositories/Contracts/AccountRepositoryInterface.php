<?php

namespace App\Repositories\Contracts;

use App\DTOs\AccountDTO;

interface AccountRepositoryInterface
{
    public function all(): array;
    public function find(int $id): ?AccountDTO;
    public function create(array $data): AccountDTO;
    public function update(int $id, array $data): ?AccountDTO;
    public function delete(int $id): bool;
}
