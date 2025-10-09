<?php

namespace App\Contracts;

interface AuthServiceInterface
{
    /**
     * Yeni bir kullanıcıyı kaydeder ve bir API tokenı döndürür.
     */
    public function register(array $data): array;

    /**
     * Kullanıcıyı sisteme giriş yapar ve bir API tokenı döndürür.
     */
    public function login(array $data): ?array;
}
