<?php

namespace App\Services\Transfer;

use App\Repositories\Contracts\TransferRepositoryInterface;
use App\DTOs\TransferDTO;

readonly class FindTransferService
{
    public function __construct(
        private TransferRepositoryInterface $transferRepository
    ) {}

    public function execute(int $id): ?TransferDTO
    {
        return $this->transferRepository->find($id);
    }
}
