<?php

namespace App\Services\Transfer;

use App\Repositories\Contracts\TransferRepositoryInterface;

readonly class DeleteTransferService
{
    public function __construct(
        private TransferRepositoryInterface $transferRepository
    ) {}

    public function execute(int $id): bool
    {
        return $this->transferRepository->delete($id);
    }
}
