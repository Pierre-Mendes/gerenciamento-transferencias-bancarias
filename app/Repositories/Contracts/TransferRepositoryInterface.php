<?php

namespace App\Repositories\Contracts;

use App\DTOs\TransferDTO;

interface TransferRepositoryInterface
{
    public function all(): array;
    public function find(int $id): ?TransferDTO;
    public function create(array $data): TransferDTO;
    public function update(int $id, array $data): ?TransferDTO;
    public function delete(int $id): bool;
}
