<?php

namespace App\Repositories;

use App\Models\Transfer;
use App\DTOs\TransferDTO;
use App\Repositories\Contracts\TransferRepositoryInterface;

class TransferRepository implements TransferRepositoryInterface
{
    public function all(): array
    {
        return Transfer::all()->map(fn($transfer) => new TransferDTO($transfer->toArray()))->all();
    }

    public function find(int $id): ?TransferDTO
    {
        $transfer = Transfer::find($id);
        return $transfer ? new TransferDTO($transfer->toArray()) : null;
    }

    public function create(array $data): TransferDTO
    {
        $transfer = Transfer::create($data);
        return new TransferDTO($transfer->toArray());
    }

    public function update(int $id, array $data): ?TransferDTO
    {
        $transfer = Transfer::find($id);
        if ($transfer) {
            $transfer->update($data);
            return new TransferDTO($transfer->toArray());
        }
        return null;
    }

    public function delete(int $id): bool
    {
        $transfer = Transfer::find($id);
        if ($transfer) {
            return $transfer->delete();
        }
        return false;
    }
}
