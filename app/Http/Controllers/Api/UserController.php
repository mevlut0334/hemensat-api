<?php

namespace App\Http\Controllers\Api; // Namespace değişti

use App\Contracts\UserServiceInterface;
use App\Http\Controllers\Controller; // Controller sınıfı doğru şekilde kullanıldı
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Tüm kullanıcıları listeler.
     */
    public function index()
    {
        $users = $this->userService->getAllUsers();
        return UserResource::collection($users);
    }

    /**
     * Yeni bir kullanıcı oluşturur.
     */
    public function store(StoreUserRequest $request)
    {
        $user = $this->userService->createUser($request->validated());
        return new UserResource($user);
    }

    /**
     * Belirli bir kullanıcıyı gösterir.
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Belirli bir kullanıcıyı günceller.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $user = $this->userService->updateUser($user, $request->validated());
        return new UserResource($user);
    }

    /**
     * Belirli bir kullanıcıyı siler.
     */
    public function destroy(User $user)
    {
        $this->userService->deleteUser($user);
        return response()->json(['message' => 'Kullanıcı başarıyla silindi.'], 200);
    }
}
