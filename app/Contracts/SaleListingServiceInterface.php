<?php



namespace App\Contracts;

use App\Models\SaleListing;

interface SaleListingServiceInterface
{
    // === BASIC CRUD ===
    public function getListingById(int $id): ?array;
    public function getListingByIdOrFail(int $id): array;
    public function createListing(array $data): SaleListing;
    public function updateListing(int $id, array $data): bool;
    public function deleteListing(int $id): bool;

    // === LISTING QUERIES ===
    public function getPublishedListings(int $perPage = 20): array;
    public function getListingsWithFilters(array $filters, int $perPage = 20): array;
    public function getUserListings(int $userId, int $perPage = 20): array;
    public function getListingForDetail(int $id): ?array;

    /**
     * Get filtered sale listings
     *
     * @param array $filters
     * @param array $sorting
     * @param int $perPage
     * @return array
     */
    public function getFilteredListings(array $filters = [], array $sorting = [], int $perPage = 15);

    // === POPULAR & FEATURED ===
    public function getPopularListings(int $limit = 10): array;
    public function getRecentListings(int $limit = 10): array;
    public function getRecentlyActiveListings(int $days = 30, int $limit = 10): array;

    // === SEARCH ===
    public function searchListings(string $query, array $filters = [], int $perPage = 20): array;

    // === STATUS OPERATIONS ===
    public function publishListing(int $id): bool;
    public function deactivateListing(int $id): bool;
    public function markAsSold(int $id): bool;

    // === STATISTICS ===
    public function getListingStats(int $id): array;
    public function getUserListingStats(int $userId): array;

    // === RELATIONSHIPS ===
    public function getListingWithImages(int $id): ?array;
    public function getListingWithOffers(int $id): ?array;
    public function getListingWithAllRelations(int $id): ?array;

    // === BULK OPERATIONS ===
    public function bulkUpdateStatus(array $ids, string $status): int;
    public function deleteUserListings(int $userId): int;
}
