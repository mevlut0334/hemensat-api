<?php

namespace App\Contracts;

use Illuminate\Support\Collection;
use App\Models\Image; // Image Modelini kullanmak için eklendi (Doğru yolu kullandığınızdan emin olun)

interface ImageServiceInterface
{
    // === BASIC CRUD ===
    // DÜZELTME: ?array yerine ?Image
    public function getImageById(int $id): ?Image;
    // DÜZELTME: array yerine Image
    public function createImage(array $data): Image;
    public function updateImage(int $id, array $data): bool;
    public function deleteImage(int $id): bool;

    // === LISTING IMAGES ===
    // DÜZELTME: array yerine Collection
    public function getListingImages(int $listingId): Collection;
    // DÜZELTME: array yerine Collection
    public function getActiveImages(int $listingId): Collection;
    // DÜZELTME: ?array yerine ?Image
    public function getPrimaryImage(int $listingId): ?Image;
    // DÜZELTME: ?array yerine ?Image
    public function getFirstImage(int $listingId): ?Image;

    // === IMAGE MANAGEMENT ===
    public function setPrimaryImage(int $imageId, int $listingId): bool;
    public function reorderImages(int $listingId, array $imageOrder): bool;
    public function updateImageStatus(int $imageId, string $status): bool;

    // === BULK OPERATIONS ===
    public function createMultipleImages(array $imagesData): Collection; // <-- Zaten doğruydu
    public function deleteListingImages(int $listingId): int;
    // DÜZELTME: array yerine Collection
    public function getFailedImages(int $listingId): Collection;
    // DÜZELTME: array yerine Collection
    public function getProcessingImages(int $listingId): Collection;

    // === FILE MANAGEMENT ===
    // DÜZELTME: array yerine Collection
    public function getImagesByStatus(string $status): Collection;
    public function cleanupFailedImages(int $olderThanHours = 24): int;
    public function validateImageExists(int $imageId): bool;

    // === STATISTICS ===
    public function getImageStats(int $listingId): array; // Dizi döndürmeye devam edebilir
    public function getTotalImagesSize(int $listingId): int;

    // === HELPER METHODS ===
    public function processListingImages(int $listingId, array $images): bool;
}
