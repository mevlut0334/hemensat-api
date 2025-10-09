<?php

namespace App\Contracts;

use Illuminate\Support\Collection;

interface ImageRepositoryInterface
{
    public function find(int $id);
    public function create(array $data);
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;

    // === LISTING IMAGES ===
    public function getListingImages(int $listingId): Collection;
    public function getActiveImages(int $listingId): Collection;
    public function getPrimaryImage(int $listingId);
    public function getFirstImage(int $listingId);

    // === IMAGE MANAGEMENT ===
    public function setPrimaryImage(int $imageId, int $listingId): bool;
    public function reorderImages(int $listingId, array $imageOrder): bool;
    public function updateImageStatus(int $imageId, string $status): bool;

    // === BULK OPERATIONS ===
    public function createMultiple(array $imagesData): Collection;
    public function deleteByListingId(int $listingId): int; // <-- eklendi
    public function getImagesByStatusForListing(int $listingId, string $status): Collection; // <-- eklendi

    // === FILE MANAGEMENT ===
    public function getImagesByStatus(string $status): Collection;
    public function cleanupFailedImages(int $olderThanHours): int;
    public function exists(int $imageId): bool; // <-- eklendi

    // === STATISTICS ===
    public function getImageStats(int $listingId): array;
    public function getTotalImagesSize(int $listingId): int;
}
