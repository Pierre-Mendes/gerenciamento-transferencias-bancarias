<?php

namespace App\DTOs;

class UserDTO
{
    public string $name;
    public string $cpf;
    public string $email;
    public ?string $password;

    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->cpf = $data['cpf'];
        $this->email = $data['email'];
        $this->password = $data['password'] ?? null;
    }
}
