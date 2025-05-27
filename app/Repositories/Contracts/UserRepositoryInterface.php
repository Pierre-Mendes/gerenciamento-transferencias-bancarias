<?php

namespace App\Repositories\Contracts;

use App\Models\User;
use App\DTOs\UserDTO;

interface UserRepositoryInterface
{
    public function all(): array;
    public function find(int $id): ?UserDTO;
    public function create(array $data): UserDTO;
    public function update(int $id, array $data): ?UserDTO;
    public function delete(int $id): bool;
}
