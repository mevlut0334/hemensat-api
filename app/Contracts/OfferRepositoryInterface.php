<?php

namespace App\Contracts;

use App\Models\Offer;
use Illuminate\Database\Eloquent\Collection;

interface OfferRepositoryInterface
{
    public function create(array $data): Offer;
    public function getOffersForListing(int $listingId, string $listingType): Collection;
    public function updateStatus(int $offerId, string $status): bool;
    public function findById(int $offerId): ?Offer;

    public function getOffersForUser(int $userId): Collection;  //burası eklendi

     public function getSentOffersByUser(int $userId): Collection;

      public function deleteOffer(int $offerId): bool;
}
