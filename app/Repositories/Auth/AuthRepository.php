<?php

namespace App\Repositories\Auth;

use App\Models\User;

class AuthRepository
{
    public function findUserByEmail(string $email) : ?User {
        return User::where('email', $email)->first();
    }

    public function createUser(User $user) : void
    {
        $user->save();
    }

}
