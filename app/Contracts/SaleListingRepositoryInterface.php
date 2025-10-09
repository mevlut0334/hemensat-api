<?php

namespace App\Contracts;

use App\Models\SaleListing;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface SaleListingRepositoryInterface
{
    // === BASIC CRUD ===

    public function find(int $id): ?SaleListing;

    public function findOrFail(int $id): SaleListing;

    public function create(array $data): SaleListing;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;

    // === LISTING QUERIES ===

    public function getPublishedListings(int $perPage = 20): LengthAwarePaginator;

    public function getListingsWithFilters(array $filters, int $perPage = 20): LengthAwarePaginator;

    /**
     * Get filtered listings with new filter system
     */
    public function getFilteredListings(array $filters = [], array $sorting = [], int $perPage = 15): LengthAwarePaginator;

    public function getUserListings(int $userId, int $perPage = 20): LengthAwarePaginator;

    public function getListingForDetail(int $id): ?SaleListing;

    // === POPULAR & FEATURED ===

    public function getPopularListings(int $limit = 10): Collection;

    public function getRecentListings(int $limit = 10): Collection;

    public function getRecentlyActiveListings(int $days = 30, int $limit = 10): Collection;

    // === SEARCH ===

    public function searchListings(string $query, array $filters = [], int $perPage = 20): LengthAwarePaginator;

    // === STATUS OPERATIONS ===

    public function publishListing(int $id): bool;

    public function deactivateListing(int $id): bool;

    public function markAsSold(int $id): bool;

    // === STATISTICS ===

    public function getListingStats(int $id): array;

    public function getUserListingStats(int $userId): array;

    // === RELATIONSHIPS ===

    public function getListingWithImages(int $id): ?SaleListing;

    public function getListingWithOffers(int $id): ?SaleListing;

    public function getListingWithAllRelations(int $id): ?SaleListing;

    // === BULK OPERATIONS ===

    public function bulkUpdateStatus(array $ids, string $status): int;

    public function deleteUserListings(int $userId): int;
}
