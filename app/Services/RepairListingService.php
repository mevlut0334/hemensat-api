<?php

namespace App\Services;

use App\Contracts\RepairListingRepositoryInterface;
use App\Contracts\RepairListingServiceInterface;
use App\Http\Resources\RepairListingResource;
use Illuminate\Support\Facades\Log; // Sadece kritik hata logları için tutulur

class RepairListingService implements RepairListingServiceInterface
{
    protected RepairListingRepositoryInterface $repository;

    public function __construct(RepairListingRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    // === BASIC CRUD ===

    public function getListingById(int $id): ?array
    {
        $listing = $this->repository->find($id);
        return $listing ? (new RepairListingResource($listing))->resolve() : null;
    }

    public function getListingByIdOrFail(int $id): array
    {
        $listing = $this->repository->findOrFail($id);
        return (new RepairListingResource($listing))->resolve();
    }

    /**
     * Model nesnesini döndürür (Resource olmadan)
     * Controller'da kullanıcı yetkisi kontrolü için gerekli
     */
    public function getListingModelById(int $id): ?\App\Models\RepairListing
    {
        return $this->repository->find($id);
    }

    // ⚠️ Artık sadece ilan verisi alınıyor — resimler controller'da işleniyor
    public function createListing(array $data): \App\Models\RepairListing
    {
        // ❌ Geliştirme logu kaldırıldı: '🔧 RepairListingService@createListing called'

        $result = $this->repository->create($data);

        // ❌ Geliştirme logu kaldırıldı: '✅ Repository create returned'

        return $result;
    }

    public function updateListing(int $id, array $data): bool
    {
        return $this->repository->update($id, $data);
    }

    public function deleteListing(int $id): bool
    {
        return $this->repository->delete($id);
    }

    // === LISTING QUERIES ===

    public function getPublishedListings(int $perPage = 20): array
    {
        $paginator = $this->repository->getPublishedListings($perPage);
        return RepairListingResource::collection($paginator)->response()->getData(true);
    }

    public function getListingsWithFilters(array $filters, int $perPage = 20): array
    {
        $paginator = $this->repository->getListingsWithFilters($filters, $perPage);
        return RepairListingResource::collection($paginator)->response()->getData(true);
    }

    /**
 * Get filtered repair listings
 */
public function getFilteredListings(array $filters = [], array $sorting = [], int $perPage = 15): array
{
    $paginator = $this->repository->getFilteredListings($filters, $sorting, $perPage);
    return RepairListingResource::collection($paginator)->response()->getData(true);
}

    public function getUserListings(int $userId, int $perPage = 20): array
    {
        $paginator = $this->repository->getUserListings($userId, $perPage);
        return RepairListingResource::collection($paginator)->response()->getData(true);
    }

    public function getListingForDetail(int $id): ?array
    {
        $listing = $this->repository->getListingForDetail($id);
        return $listing ? (new RepairListingResource($listing))->resolve() : null;
    }

    // === POPULAR & RECENT ===

    public function getPopularListings(int $limit = 10): array
    {
        $collection = $this->repository->getPopularListings($limit);
        return RepairListingResource::collection($collection)->resolve();
    }

    public function getRecentListings(int $limit = 10): array
    {
        $collection = $this->repository->getRecentListings($limit);
        return RepairListingResource::collection($collection)->resolve();
    }

    public function getRecentlyActiveListings(int $days = 30, int $limit = 10): array
    {
        $collection = $this->repository->getRecentlyActiveListings($days, $limit);
        return RepairListingResource::collection($collection)->resolve();
    }

    // === SEARCH ===

    public function searchListings(string $query, array $filters = [], int $perPage = 20): array
    {
        $paginator = $this->repository->searchListings($query, $filters, $perPage);
        return RepairListingResource::collection($paginator)->response()->getData(true);
    }

    // === STATUS OPERATIONS ===

    public function publishListing(int $id): bool
    {
        return $this->repository->publishListing($id);
    }

    public function deactivateListing(int $id): bool
    {
        return $this->repository->deactivateListing($id);
    }

    public function markAsCompleted(int $id): bool
    {
        return $this->repository->markAsCompleted($id);
    }

    // === STATISTICS ===

    public function getListingStats(int $id): array
    {
        return $this->repository->getListingStats($id);
    }

    public function getUserListingStats(int $userId): array
    {
        return $this->repository->getUserListingStats($userId);
    }

    // === RELATIONSHIPS ===

    public function getListingWithImages(int $id): ?array
    {
        $listing = $this->repository->getListingWithImages($id);
        return $listing ? (new RepairListingResource($listing))->resolve() : null;
    }

    public function getListingWithAllRelations(int $id): ?\App\Models\RepairListing
    {
        return $this->repository->getListingWithAllRelations($id);
    }




/**
 * İlanın tekliflerini sadece yetkili kullanıcılar için döndürür
 */
public function getListingWithOffersForUser(int $listingId, int $userId, bool $isOwner = false): ?\App\Models\RepairListing
{
    return $this->repository->getListingWithOffersForUser($listingId, $userId, $isOwner);
}

    // === BULK OPERATIONS ===

    public function bulkUpdateStatus(array $ids, string $status): int
    {
        return $this->repository->bulkUpdateStatus($ids, $status);
    }

    public function deleteUserListings(int $userId): int
    {
        return $this->repository->deleteUserListings($userId);
    }
}
