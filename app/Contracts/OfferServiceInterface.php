<?php

namespace App\Contracts;

use App\Models\Offer;
use Illuminate\Database\Eloquent\Collection;

interface OfferServiceInterface
{
    public function submitOffer(array $data): Offer;
    public function acceptOffer(int $offerId): bool;
    public function rejectOffer(int $offerId): bool;
    public function getOffersForListing(int $listingId, string $listingType): Collection;

    // Teklifi ID'ye göre bulma metodu
    public function findOfferById(int $offerId): ?Offer;

    public function getOffersForUser(int $userId): Collection;  //burası eklendi

    public function getSentOffersByUser(int $userId): Collection;

     public function deleteOffer(int $offerId): bool;
}
