<?php

namespace App\Contracts;

use App\Models\AuthUser;

interface AuthRepositoryInterface
{
    /**
     * Yeni bir kullanıcı oluşturur.
     */
    public function create(array $data): AuthUser;

    /**
     * E-posta adresine göre bir kullanıcıyı bulur.
     */
    public function findByEmail(string $email): ?AuthUser;
}
