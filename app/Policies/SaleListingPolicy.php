<?php

namespace App\Policies;

use App\Models\User;
use App\Models\SaleListing;
use Illuminate\Auth\Access\HandlesAuthorization;


class SaleListingPolicy
{
    use HandlesAuthorization;

    /**
     * Tüm satış ilanlarını görüntüleyebilir mi?
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Belirli bir satış ilanını görüntüleyebilir mi?
     */
    public function view(User $user, SaleListing $saleListing)
    {
        // Kendi ilanını her zaman görebilir
        if ($saleListing->user_id === $user->id) {
            return true;
        }

        // Başkasının ilanını görmek için abone olmalı
        return $user->is_subscriber;
    }

    /**
     * Satış ilanı oluşturabilir mi?
     */
    public function create(User $user)
    {
        return true; // Tüm giriş yapmış kullanıcılar
    }

    /**
     * Bu satış ilanını güncelleyebilir mi?
     */
    public function update(User $user, SaleListing $saleListing)
    {
        return $saleListing->user_id === $user->id;
    }

    /**
     * Bu satış ilanını silebilir mi?
     */
    public function delete($user, SaleListing $saleListing)  // User type hint'ini kaldırdım
{
    return $user->id === $saleListing->user_id;
}

    /**
     * Bu satış ilanını yayınlayabilir mi?
     */
    public function publish(User $user, SaleListing $saleListing)
    {
        return $saleListing->user_id === $user->id;
    }

    /**
     * Bu satış ilanını deaktif edebilir mi?
     */
    public function deactivate(User $user, SaleListing $saleListing)
    {
        return $saleListing->user_id === $user->id;
    }

    /**
     * Bu satış ilanını satıldı olarak işaretleyebilir mi?
     */
    public function markAsSold(User $user, SaleListing $saleListing)
    {
        return $saleListing->user_id === $user->id;
    }
}
