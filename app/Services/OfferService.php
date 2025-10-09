<?php

namespace App\Services;

use App\Contracts\OfferServiceInterface;
use App\Contracts\OfferRepositoryInterface;
use App\Models\Offer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class OfferService implements OfferServiceInterface
{
    protected $offerRepository;

    public function __construct(OfferRepositoryInterface $offerRepository)
    {
        $this->offerRepository = $offerRepository;
    }

    public function submitOffer(array $data): Offer
    {
        if (!isset($data['offerable_type']) || !isset($data['offerable_id'])) {
            throw new InvalidArgumentException("Teklifin yapılacağı ilan bilgileri eksik.");
        }
        return $this->offerRepository->create($data);
    }

    public function acceptOffer(int $offerId): bool
    {
        $offer = $this->offerRepository->findById($offerId);
        if (!$offer || $offer->status !== 'pending') {
            return false;
        }

        return DB::transaction(function () use ($offer, $offerId) {
            $offer->update([
                'status' => 'accepted',
                'accepted_at' => now(),
            ]);

            $this->offerRepository->getOffersForListing($offer->offerable_id, $offer->offerable_type)
                ->where('status', 'pending')
                ->where('id', '!=', $offerId)
                ->each(function (Offer $otherOffer) {
                    $otherOffer->update(['status' => 'rejected']);
                });
            return true;
        });
    }

    public function rejectOffer(int $offerId): bool
    {
        return $this->offerRepository->updateStatus($offerId, 'rejected');
    }

    public function getOffersForListing(int $listingId, string $listingType): Collection
    {
        return $this->offerRepository->getOffersForListing($listingId, $listingType);
    }

    public function findOfferById(int $offerId): ?Offer
    {
        return $this->offerRepository->findById($offerId);
    }

    // Bu method'u ekleyin
    public function getOffersForUser(int $userId): Collection
    {
        return $this->offerRepository->getOffersForUser($userId);
    }

    public function getSentOffersByUser(int $userId): Collection
    {
        return $this->offerRepository->getSentOffersByUser($userId);
    }

    public function deleteOffer(int $offerId): bool
    {
        $offer = $this->offerRepository->findById($offerId);

        if (!$offer) {
            return false;
        }

        // Sadece pending durumundaki teklifler silinebilir
        if ($offer->status !== 'pending') {
            return false;
        }

        return $this->offerRepository->deleteOffer($offerId);
    }
}
