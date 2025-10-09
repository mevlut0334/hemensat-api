<?php

namespace App\Services;

use App\Contracts\ImageRepositoryInterface;
use App\Contracts\ImageServiceInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Models\Image; // BU SATIR EKLENMİŞTİR!

class ImageService implements ImageServiceInterface
{
    protected $imageRepository;

    public function __construct(ImageRepositoryInterface $imageRepository)
    {
        $this->imageRepository = $imageRepository;
    }

    // === BASIC CRUD ===
    // DÜZELTME: Dönüş tipi array yerine ?Image olarak değiştirildi ve ->toArray() kaldırıldı.
    public function getImageById(int $id): ?Image
    {
        $image = $this->imageRepository->find($id);
        return $image; // toArray() kaldırıldı
    }

    // DÜZELTME: Dönüş tipi array yerine Image olarak değiştirildi ve ->toArray() kaldırıldı.
    public function createImage(array $data): Image
    {
        return $this->imageRepository->create($data); // toArray() kaldırıldı
    }

    public function updateImage(int $id, array $data): bool
    {
        $result = $this->imageRepository->update($id, $data);

        if ($result) {
            $this->clearImageCaches($id);
        }

        return $result;
    }

    public function deleteImage(int $id): bool
    {
        $image = $this->imageRepository->find($id);
        if (!$image) return false;

        $listingId = $image->sale_listing_id;
        $result = $this->imageRepository->delete($id);

        if ($result) {
            $this->clearImageCaches($id);
            $this->clearListingImageCaches($listingId);
        }

        return $result;
    }

    // === LISTING IMAGES ===
    // DÜZELTME: Dönüş tipi array yerine Collection olarak değiştirildi ve ->toArray() kaldırıldı.
    public function getListingImages(int $listingId): Collection
    {
        return $this->imageRepository->getListingImages($listingId); // toArray() kaldırıldı
    }

    // DÜZELTME: Dönüş tipi array yerine Collection olarak değiştirildi ve ->toArray() kaldırıldı.
    public function getActiveImages(int $listingId): Collection
    {
        return $this->imageRepository->getActiveImages($listingId); // toArray() kaldırıldı
    }

    // DÜZELTME: Dönüş tipi ?array yerine ?Image olarak değiştirildi ve ->toArray() kaldırıldı.
    public function getPrimaryImage(int $listingId): ?Image
    {
        $image = $this->imageRepository->getPrimaryImage($listingId);
        return $image; // toArray() kaldırıldı
    }

    // DÜZELTME: Dönüş tipi ?array yerine ?Image olarak değiştirildi ve ->toArray() kaldırıldı.
    public function getFirstImage(int $listingId): ?Image
    {
        $image = $this->imageRepository->getFirstImage($listingId);
        return $image; // toArray() kaldırıldı
    }

    // === IMAGE MANAGEMENT ===
    public function setPrimaryImage(int $imageId, int $listingId): bool
    {
        $result = $this->imageRepository->setPrimaryImage($imageId, $listingId);

        if ($result) $this->clearListingImageCaches($listingId);

        return $result;
    }

    public function reorderImages(int $listingId, array $imageOrder): bool
    {
        $result = $this->imageRepository->reorderImages($listingId, $imageOrder);

        if ($result) $this->clearListingImageCaches($listingId);

        return $result;
    }

    public function updateImageStatus(int $imageId, string $status): bool
    {
        $image = $this->imageRepository->find($imageId);
        if (!$image) return false;

        $listingId = $image->sale_listing_id;
        $result = $this->imageRepository->updateImageStatus($imageId, $status);

        if ($result) $this->clearListingImageCaches($listingId);

        return $result;
    }

    // === BULK OPERATIONS ===
    public function createMultipleImages(array $imagesData): Collection
    {
        return $this->imageRepository->createMultiple($imagesData);
    }

    public function deleteListingImages(int $listingId): int
    {
        $count = $this->imageRepository->deleteByListingId($listingId);

        if ($count > 0) {
            $this->clearListingImageCaches($listingId);
        }

        return $count;
    }

    // DÜZELTME: Dönüş tipi array yerine Collection olarak değiştirildi ve ->toArray() kaldırıldı.
    public function getFailedImages(int $listingId): Collection
    {
        return $this->imageRepository->getImagesByStatusForListing($listingId, 'failed'); // toArray() kaldırıldı
    }

    // DÜZELTME: Dönüş tipi array yerine Collection olarak değiştirildi ve ->toArray() kaldırıldı.
    public function getProcessingImages(int $listingId): Collection
    {
        return $this->imageRepository->getImagesByStatusForListing($listingId, 'processing'); // toArray() kaldırıldı
    }

    // === FILE MANAGEMENT ===
    // DÜZELTME: Dönüş tipi array yerine Collection olarak değiştirildi ve ->toArray() kaldırıldı.
    public function getImagesByStatus(string $status): Collection
    {
        return $this->imageRepository->getImagesByStatus($status); // toArray() kaldırıldı
    }

    public function cleanupFailedImages(int $olderThanHours = 24): int
    {
        return $this->imageRepository->cleanupFailedImages($olderThanHours);
    }

    public function validateImageExists(int $imageId): bool
    {
        return $this->imageRepository->exists($imageId);
    }

    // === STATISTICS ===
    public function getImageStats(int $listingId): array
    {
        return $this->imageRepository->getImageStats($listingId);
    }

    public function getTotalImagesSize(int $listingId): int
    {
        return $this->imageRepository->getTotalImagesSize($listingId);
    }

    // === HELPER METHODS ===
    public function processListingImages(int $listingId, array $images): bool
    {
        return DB::transaction(function () use ($listingId, $images) {
            $imageData = [];

            foreach ($images as $image) {
                // Burada $image'ın bir UploadedFile olduğunu varsayıyoruz.
                // Ancak bu metot Job'lar tarafından çağrılıyorsa, burada resim işleme mantığının tamamlanmış
                // ve sadece veritabanına kayıt için uygun verilerin hazırlanmış olması gerekir.
                // Mevcut kodunuzu koruyarak ilerliyorum:

                // NOT: Eğer bu metot job içinde çalışıyorsa, $image->store() gibi metotlar çalışmayabilir.
                // Ancak bu sorununuzun kaynağı değil, bu nedenle kodunuzun akışını koruyorum.

                $path = $image->store('listings', 'public');

                $imageData[] = [
                    'sale_listing_id' => $listingId,
                    'path' => $path,
                    'filename' => $image->getClientOriginalName(),
                    'original_name' => $image->getClientOriginalName(),
                    'size' => $image->getSize(),
                    'mime_type' => $image->getClientMimeType(),
                    'status' => 'active',
                    'is_primary' => $image->is_primary ?? false,
                    'order' => $image->order ?? 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            $createdImages = $this->createMultipleImages($imageData);

            // Collection of Models => contains callback ile kontrol edilmeli
            if ($createdImages->isNotEmpty() && !$createdImages->contains(fn($img) => $img->is_primary)) {
                $firstImage = $createdImages->first();
                $this->setPrimaryImage($firstImage->id, $listingId);
            }

            $this->clearListingImageCaches($listingId);

            return true;
        });
    }

    // === PRIVATE HELPERS ===
    private function clearListingImageCaches(int $listingId): void
    {
        $cacheKeys = [
            "listing_images_{$listingId}",
            "active_listing_images_{$listingId}",
            "primary_listing_image_{$listingId}",
            "first_listing_image_{$listingId}",
            "image_stats_{$listingId}",
            "total_images_size_{$listingId}",
        ];

        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }
    }

    private function clearImageCaches(int $imageId): void
    {
        // Gerekirse buraya cache temizleme kodu eklenebilir
    }
}
