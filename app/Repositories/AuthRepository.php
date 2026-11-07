<?php

namespace App\Repositories;

use App\Contracts\AuthRepositoryInterface;
use App\Models\AuthUser;

class AuthRepository implements AuthRepositoryInterface
{
    /**
     * Yeni bir kullanıcı oluşturur.
     */
    public function create(array $data): AuthUser
    {
        $user = new AuthUser($data);
        $user->is_subscribed = true;
        $user->save();

        return $user;
    }

    /**
     * E-posta adresine göre bir kullanıcıyı bulur.
     */
    public function findByEmail(string $email): ?AuthUser
    {
        return AuthUser::where('email', $email)->first();
    }
}
