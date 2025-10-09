<?php

namespace App\Contracts;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface SubscriptionServiceInterface
{
    public function listUsers(int $perPage = 15, ?string $email = null): LengthAwarePaginator;

    public function toggleUserSubscription(User $user): User;

    public function setUserSubscription(User $user, bool $status): User;
}
