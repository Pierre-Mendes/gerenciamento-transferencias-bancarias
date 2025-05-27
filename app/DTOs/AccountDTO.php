<?php

namespace App\DTOs;

class AccountDTO
{
    public ?int $id;
    public int $user_id;
    public string $number;
    public float $balance;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? null;
        $this->user_id = $data['user_id'];
        $this->number = $data['number'];
        $this->balance = (float) $data['balance'];
    }
}
