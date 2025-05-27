<?php

namespace App\Services\Transfer;

use App\Repositories\Contracts\TransferRepositoryInterface;

readonly class ListTransferService
{
    public function __construct(
        private TransferRepositoryInterface $transferRepository
    ) {}

    public function execute(): array
    {
        return $this->transferRepository->all();
    }
}
