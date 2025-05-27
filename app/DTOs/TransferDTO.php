<?php

namespace App\DTOs;

class TransferDTO
{
    public ?int $id;
    public ?int $from_account_id;
    public ?int $to_account_id;
    public float $amount;
    public string $type; // deposit, withdraw, transfer
    public string $status;
    public ?string $processed_at;
    public ?string $created_at;
    public ?string $updated_at;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? null;
        $this->from_account_id = $data['from_account_id'] ?? null;
        $this->to_account_id = $data['to_account_id'] ?? null;
        $this->amount = (float) $data['amount'];
        $this->type = $data['type'];
        $this->status = $data['status'] ?? 'pending';
        $this->processed_at = $data['processed_at'] ?? null;
        $this->created_at = $data['created_at'] ?? null;
        $this->updated_at = $data['updated_at'] ?? null;
    }
}
