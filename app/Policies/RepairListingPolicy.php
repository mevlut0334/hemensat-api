<?php

namespace App\Policies;

use App\Models\RepairListing;
use App\Models\AuthUser; // User yerine AuthUser kullanıyoruz
use Illuminate\Auth\Access\Response;

class RepairListingPolicy
{
    /**
     * Kullanıcının tamir ilanını görüntüleyip görüntüleyemeyeceğini belirler.
     */
    public function view(AuthUser $user, RepairListing $repairListing): bool
    {
        // Herhangi bir giriş yapmış kullanıcı tamir ilanlarını görüntüleyebilir
        // (Eğer özel kısıtlamalar varsa burada kontrol edilir)
        return true;
    }

    /**
     * Kullanıcının tamir ilanını oluşturup oluşturamayacağını belirler.
     */
    public function create(AuthUser $user): bool
    {
        // Herhangi bir kayıtlı kullanıcı tamir ilanı oluşturabilir
        return true;
    }

    /**
     * Kullanıcının tamir ilanını güncelleyip güncelleyemeyeceğini belirler.
     */
    public function update(AuthUser $user, RepairListing $repairListing): bool
    {
        // Sadece ilan sahibi kendi ilanını güncelleyebilir
        return $user->id === $repairListing->user_id;
    }

    /**
     * Kullanıcının tamir ilanını silip silemeyeceğini belirler.
     */
    public function delete(AuthUser $user, RepairListing $repairListing): bool
    {
        // Sadece ilan sahibi kendi ilanını silebilir
        return $user->id === $repairListing->user_id;
    }

    /**
     * Kullanıcının tamir ilanını geri yükleyip yükleyemeyeceğini belirler.
     */
    public function restore(AuthUser $user, RepairListing $repairListing): bool
    {
        // Sadece ilan sahibi kendi ilanını geri yükleyebilir
        return $user->id === $repairListing->user_id;
    }

    /**
     * Kullanıcının tamir ilanını kalıcı olarak silip silemeyeceğini belirler.
     */
    public function forceDelete(AuthUser $user, RepairListing $repairListing): bool
    {
        // Sadece ilan sahibi kendi ilanını kalıcı olarak silebilir
        return $user->id === $repairListing->user_id;
    }

    /**
     * Kullanıcının tamir ilanını yayınlayıp yayınlayamayacağını belirler.
     */
    public function publish(AuthUser $user, RepairListing $repairListing): bool
    {
        // Sadece ilan sahibi kendi ilanını yayınlayabilir
        return $user->id === $repairListing->user_id;
    }

    /**
     * Kullanıcının tamir ilanını tamamlandı olarak işaretleyip işaretleyemeyeceğini belirler.
     */
    public function complete(AuthUser $user, RepairListing $repairListing): bool
    {
        // Sadece ilan sahibi kendi ilanını tamamlandı olarak işaretleyebilir
        return $user->id === $repairListing->user_id;
    }
}
