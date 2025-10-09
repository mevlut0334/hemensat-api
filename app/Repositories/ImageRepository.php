<?php

namespace App\Repositories;

use App\Contracts\ImageRepositoryInterface;
use App\Models\Image;
use Illuminate\Support\Collection;

class ImageRepository implements ImageRepositoryInterface
{
    public function find(int $id): ?Image
    {
        return Image::find($id);
    }

    public function create(array $data): Image
    {
        return Image::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $image = $this->find($id);
        return $image ? $image->update($data) : false;
    }

    public function delete(int $id): bool
    {
        $image = $this->find($id);
        return $image ? (bool) $image->delete() : false;
    }

    // === LISTING IMAGES - POLYMORPHIC İLİŞKİ ===
    public function getListingImages(int $listingId): Collection
    {
        return Image::where('imageable_id', $listingId)
            ->where('imageable_type', 'App\\Models\\SaleListing')
            ->get();
    }

    public function getActiveImages(int $listingId): Collection
    {
        return Image::where('imageable_id', $listingId)
            ->where('imageable_type', 'App\\Models\\SaleListing')
            ->where('status', 'active')
            ->get();
    }

    public function getPrimaryImage(int $listingId): ?Image
    {
        return Image::where('imageable_id', $listingId)
            ->where('imageable_type', 'App\\Models\\SaleListing')
            ->where('is_primary', true)
            ->first();
    }

    public function getFirstImage(int $listingId): ?Image
    {
        return Image::where('imageable_id', $listingId)
            ->where('imageable_type', 'App\\Models\\SaleListing')
            ->orderBy('order', 'asc')
            ->first();
    }

    // === IMAGE MANAGEMENT ===
    public function setPrimaryImage(int $imageId, int $listingId): bool
    {
        // Önce tüm resimlerin is_primary'sini false yap
        Image::where('imageable_id', $listingId)
            ->where('imageable_type', 'App\\Models\\SaleListing')
            ->update(['is_primary' => false]);

        // Sonra seçili resmi primary yap
        return Image::where('id', $imageId)
            ->where('imageable_id', $listingId)
            ->where('imageable_type', 'App\\Models\\SaleListing')
            ->update(['is_primary' => true]) > 0;
    }

    public function reorderImages(int $listingId, array $imageOrder): bool
    {
        foreach ($imageOrder as $order => $id) {
            Image::where('id', $id)
                ->where('imageable_id', $listingId)
                ->where('imageable_type', 'App\\Models\\SaleListing')
                ->update(['order' => $order]);
        }
        return true;
    }

    public function updateImageStatus(int $imageId, string $status): bool
    {
        return Image::where('id', $imageId)->update(['status' => $status]) > 0;
    }

    // === BULK OPERATIONS ===
    public function createMultiple(array $imagesData): Collection
    {
        $inserted = collect();
        foreach ($imagesData as $data) {
            $inserted->push(Image::create($data));
        }
        return $inserted;
    }

    // 🔥 SORUNU ÇÖZEN METOD
    public function deleteByListingId(int $listingId): int
    {
        return Image::where('imageable_id', $listingId)
            ->where('imageable_type', 'App\\Models\\SaleListing')
            ->delete();
    }

    public function getImagesByStatusForListing(int $listingId, string $status): Collection
    {
        return Image::where('imageable_id', $listingId)
            ->where('imageable_type', 'App\\Models\\SaleListing')
            ->where('status', $status)
            ->get();
    }

    // === FILE MANAGEMENT ===
    public function getImagesByStatus(string $status): Collection
    {
        return Image::where('status', $status)->get();
    }

    public function cleanupFailedImages(int $olderThanHours): int
    {
        $threshold = now()->subHours($olderThanHours);

        return Image::where('status', 'failed')
            ->where('created_at', '<', $threshold)
            ->delete();
    }

    public function exists(int $imageId): bool
    {
        return Image::where('id', $imageId)->exists();
    }

    // === STATISTICS ===
    public function getImageStats(int $listingId): array
    {
        return [
            'total' => Image::where('imageable_id', $listingId)
                ->where('imageable_type', 'App\\Models\\SaleListing')
                ->count(),
            'active' => Image::where('imageable_id', $listingId)
                ->where('imageable_type', 'App\\Models\\SaleListing')
                ->where('status', 'active')->count(),
            'failed' => Image::where('imageable_id', $listingId)
                ->where('imageable_type', 'App\\Models\\SaleListing')
                ->where('status', 'failed')->count(),
        ];
    }

    public function getTotalImagesSize(int $listingId): int
    {
        return (int) Image::where('imageable_id', $listingId)
            ->where('imageable_type', 'App\\Models\\SaleListing')
            ->sum('size');
    }

    // === YENİ METOD: REPAIR LISTINGS İÇİN ===
    public function getRepairListingImages(int $repairListingId): Collection
    {
        return Image::where('imageable_id', $repairListingId)
            ->where('imageable_type', 'App\\Models\\RepairListing')
            ->get();
    }

    public function deleteByRepairListingId(int $repairListingId): int
    {
        return Image::where('imageable_id', $repairListingId)
            ->where('imageable_type', 'App\\Models\\RepairListing')
            ->delete();
    }
}
