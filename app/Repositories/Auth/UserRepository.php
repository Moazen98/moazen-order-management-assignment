<?php

namespace App\Repositories\Auth;

use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function create(array $data)
    {
        return User::query()->create($data);
    }

    public function findByEmail(string $email)
    {
        return User::query()->where('email', $email)->first();
    }
}
