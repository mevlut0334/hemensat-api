<?php

namespace App\Repositories;

use App\Models\User;
use App\Contracts\SubscriptionRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class SubscriptionRepository implements SubscriptionRepositoryInterface
{
    public function getAllUsersWithPagination(int $perPage = 15, ?string $email = null): LengthAwarePaginator
    {
        $query = User::with(['province', 'district']);

        if ($email) {
            $query->where('email', 'like', '%' . $email . '%');
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function toggleSubscription(User $user): User
    {
        $user->is_subscribed = !$user->is_subscribed;
        $user->save();
        return $user;
    }

    public function updateSubscriptionStatus(User $user, bool $status): User
    {
        $user->is_subscribed = $status;
        $user->save();
        return $user;
    }
}
