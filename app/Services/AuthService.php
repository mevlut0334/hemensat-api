<?php

namespace App\Services;

use App\Contracts\AuthRepositoryInterface;
use App\Contracts\AuthServiceInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService implements AuthServiceInterface
{
    protected AuthRepositoryInterface $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    /**
     * Yeni bir kullanıcıyı kaydeder ve bir API tokenı döndürür.
     */
    public function register(array $data): array
    {
        $user = $this->authRepository->create($data);

        $token = $user->createToken('auth-token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    /**
     * Kullanıcıyı sisteme giriş yapar ve bir API tokenı döndürür.
     */
    public function login(array $data): ?array
    {
        $user = $this->authRepository->findByEmail($data['email']);

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['E-posta veya şifre yanlış.'],
            ]);
        }

        $user->tokens()->delete();
        $token = $user->createToken('auth-token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }
}
