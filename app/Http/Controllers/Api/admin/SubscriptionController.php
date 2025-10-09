<?php

namespace App\Http\Controllers\Api\Admin;

use App\Contracts\SubscriptionServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateSubscriptionRequest;
use App\Http\Resources\UserSubscriptionResource;
use App\Models\User;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    protected $subscriptionService;

    public function __construct(SubscriptionServiceInterface $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * Tüm kullanıcıları listeler (pagination ve email filtreleme ile)
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $email = $request->get('email');

        $users = $this->subscriptionService->listUsers($perPage, $email);

        return UserSubscriptionResource::collection($users);
    }

    /**
     * Kullanıcının abonelik durumunu toggle eder (tek tıkla)
     */
    public function toggle(Request $request, User $user)
{
    $request->validate([
        'is_subscribed' => 'required|boolean'
    ]);

    $status = $request->input('is_subscribed');
    $updatedUser = $this->subscriptionService->setUserSubscription($user, $status);

    return response()->json([
        'success' => true,
        'message' => $status
            ? 'Kullanıcı başarıyla abone yapıldı.'
            : 'Kullanıcının aboneliği kaldırıldı.',
        'data' => new UserSubscriptionResource($updatedUser)
    ], 200);
}

    /**
     * Kullanıcının abonelik durumunu belirli bir değere set eder
     */
    public function update(UpdateSubscriptionRequest $request, User $user)
    {
        $status = $request->validated()['is_subscribed'];
        $updatedUser = $this->subscriptionService->setUserSubscription($user, $status);

        return response()->json([
            'message' => 'Abonelik durumu başarıyla güncellendi.',
            'data' => new UserSubscriptionResource($updatedUser)
        ], 200);
    }
}
