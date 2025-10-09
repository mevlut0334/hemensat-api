<?php

namespace App\Http\Controllers\Api;

use App\Contracts\AuthServiceInterface;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    protected AuthServiceInterface $authService;

    public function __construct(AuthServiceInterface $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Yeni bir kullanıcıyı kaydeder.
     */
    public function register(StoreUserRequest $request): JsonResponse
    {
        // Form Request ile doğrulanmış veriyi al
        $validated = $request->validated();

        // Servis katmanını kullanarak kayıt işlemini gerçekleştir
        $result = $this->authService->register($validated);

        // Başarılı yanıtı döndür
        return response()->json([
            'message' => 'Kullanıcı başarıyla kaydedildi.',
            'user' => $result['user'],
            'token' => $result['token'],
        ], 201);
    }

    /**
     * Mevcut bir kullanıcıyı sisteme giriş yapar.
     */
    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $result = $this->authService->login($validated);

        if (!$result) {
            return response()->json([
                'message' => 'Kimlik doğrulama başarısız.'
            ], 401);
        }

        return response()->json([
            'message' => 'Giriş başarılı.',
            'user' => $result['user'],
            'token' => $result['token'],
        ]);
    }

    /**
     * Kullanıcının oturumunu sonlandırır (token'ı siler).
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Çıkış başarılı.'
        ]);
    }
}
