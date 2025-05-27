<?php

namespace App\Services\Transfer;

use App\DTOs\TransferDTO;
use App\Repositories\Contracts\TransferRepositoryInterface;

readonly class UpdateTransferService
{
    public function __construct(
        private TransferRepositoryInterface $transferRepository
    ) {}

    public function execute(int $id, TransferDTO $dto): ?TransferDTO
    {
        $data = [
            'from_account_id' => $dto->from_account_id,
            'to_account_id' => $dto->to_account_id,
            'amount' => $dto->amount,
            'type' => $dto->type,
            'status' => $dto->status,
            'processed_at' => $dto->processed_at,
        ];
        return $this->transferRepository->update($id, $data);
    }
}
