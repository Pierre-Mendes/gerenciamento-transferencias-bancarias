<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\DTOs\UserDTO;

class UserRepository implements UserRepositoryInterface
{
    public function all(): array
    {
        return User::all()->map(fn($user) => new UserDTO($user->toArray()))->all();
    }

    public function find(int $id): ?UserDTO
    {
        $user = User::find($id);
        return $user ? new UserDTO($user->toArray()) : null;
    }

    public function create(array $data): UserDTO
    {
        $user = User::create($data);
        return new UserDTO($user->toArray());
    }

    public function update(int $id, array $data): ?UserDTO
    {
        $user = User::find($id);
        if ($user) {
            $user->update($data);
            return new UserDTO($user->toArray());
        }
        return null;
    }

    public function delete(int $id): bool
    {
        $user = User::find($id);
        if ($user) {
            return $user->delete();
        }
        return false;
    }
}
