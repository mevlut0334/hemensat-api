<?php

namespace App\Services;

use App\Contracts\SubscriptionRepositoryInterface;
use App\Contracts\SubscriptionServiceInterface;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class SubscriptionService implements SubscriptionServiceInterface
{
    protected $subscriptionRepository;

    public function __construct(SubscriptionRepositoryInterface $subscriptionRepository)
    {
        $this->subscriptionRepository = $subscriptionRepository;
    }

    public function listUsers(int $perPage = 15, ?string $email = null): LengthAwarePaginator
    {
        return $this->subscriptionRepository->getAllUsersWithPagination($perPage, $email);
    }

    public function toggleUserSubscription(User $user): User
    {
        return $this->subscriptionRepository->toggleSubscription($user);
    }

    public function setUserSubscription(User $user, bool $status): User
    {
        return $this->subscriptionRepository->updateSubscriptionStatus($user, $status);
    }
}
