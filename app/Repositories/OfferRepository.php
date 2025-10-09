<?php

namespace App\Repositories;

use App\Contracts\OfferRepositoryInterface;
use App\Models\Offer;
use Illuminate\Database\Eloquent\Collection;

class OfferRepository implements OfferRepositoryInterface
{
    public function create(array $data): Offer
    {
        return Offer::create($data);
    }

    public function getOffersForListing(int $listingId, string $listingType): Collection
    {
        return Offer::where('offerable_id', $listingId)
            ->where('offerable_type', $listingType)
            ->with(['user', 'offerable'])
            ->get();
    }

    public function updateStatus(int $offerId, string $status): bool
    {
        $offer = $this->findById($offerId);
        if (!$offer) {
            return false;
        }
        $offer->status = $status;
        return $offer->save();
    }

    public function findById(int $offerId): ?Offer
    {
        return Offer::with(['user', 'offerable'])->find($offerId);
    }

    /**
     * Kullanıcının tüm ilanlarına gelen teklifleri getirir (SADECE AKTİF İLANLARA YAPILAN TEKLİFLER)
     */
    public function getOffersForUser(int $userId): Collection
    {
        return Offer::whereHasMorph(
            'offerable',
            ['App\Models\SaleListing', 'App\Models\RepairListing'],
            function ($query) use ($userId) {
                $query->where('user_id', $userId)
                      ->where('status', 'active');
            }
        )
        ->with(['user', 'offerable' => function ($query) {
            $query->where('status', 'active');
        }])
        ->whereHas('user') // ✅ User silinmişse teklifi gösterme
        ->orderBy('created_at', 'desc')
        ->get()
        ->filter(function ($offer) {
            // ✅ Offerable null olanları filtrele (silinmiş ilanlar)
            return $offer->offerable !== null && $offer->user !== null;
        });
    }

    /**
     * Kullanıcının verdiği teklifleri getirir (SADECE AKTİF İLANLARA YAPILAN TEKLİFLER)
     */
    public function getSentOffersByUser(int $userId): Collection
    {
        return Offer::where('user_id', $userId)
            ->whereHasMorph(
                'offerable',
                ['App\Models\SaleListing', 'App\Models\RepairListing'],
                function ($query) {
                    $query->where('status', 'active');
                }
            )
            ->with(['user', 'offerable' => function ($query) {
                $query->where('status', 'active');
            }])
            ->orderBy('created_at', 'desc')
            ->get()
            ->filter(function ($offer) {
                // ✅ Offerable null olanları filtrele (silinmiş ilanlar)
                return $offer->offerable !== null;
            });
    }

    public function deleteOffer(int $offerId): bool
    {
        $offer = $this->findById($offerId);
        if (!$offer) {
            return false;
        }

        return $offer->delete();
    }
}
