<?php

namespace App\Services\Transfer;

use App\DTOs\TransferDTO;
use App\Repositories\Contracts\TransferRepositoryInterface;
use App\Services\TransferKafkaProducer;
use Junges\Kafka\Exceptions\LaravelKafkaException;

readonly class CreateTransferService
{
    public function __construct(
        private TransferRepositoryInterface $transferRepository,
        private TransferKafkaProducer $producer
    ) {}

    /**
     * @throws LaravelKafkaException
     */
    public function execute(TransferDTO $dto): TransferDTO
    {
        if ($dto->type === "deposit") {
            $transfer = $this->transferRepository->create([
                'from_account_id' => null,
                'to_account_id' => $dto->to_account_id,
                'amount' => $dto->amount,
                'type' => $dto->type,
                'status' => 'pending',
            ]);
        } elseif ($dto->type === "withdraw") {
            $transfer = $this->transferRepository->create([
                'from_account_id' => $dto->from_account_id,
                'to_account_id' => null,
                'amount' => $dto->amount,
                'type' => $dto->type,
                'status' => 'pending',
            ]);
        } elseif ($dto->type === "transfer") {
            $transfer = $this->transferRepository->create([
                'from_account_id' => $dto->from_account_id,
                'to_account_id' => $dto->to_account_id,
                'amount' => $dto->amount,
                'type' => $dto->type,
                'status' => 'pending',
            ]);
        } else {
            throw new \InvalidArgumentException('Tipo de transferência inválido.');
        }

        $this->producer->publish([
            'transfer_id' => $transfer->id,
            'from_account_id' => $transfer->from_account_id,
            'to_account_id' => $transfer->to_account_id,
            'amount' => $transfer->amount,
            'type' => $transfer->type,
        ]);

        return $transfer;
    }
}
