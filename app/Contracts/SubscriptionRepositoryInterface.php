<?php

namespace App\Contracts;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface SubscriptionRepositoryInterface
{
    public function getAllUsersWithPagination(int $perPage = 15, ?string $email = null): LengthAwarePaginator;

    public function toggleSubscription(User $user): User;

    public function updateSubscriptionStatus(User $user, bool $status): User;
}
