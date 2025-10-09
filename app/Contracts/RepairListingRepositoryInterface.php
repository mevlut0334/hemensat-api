<?php

namespace App\Contracts;

interface RepairListingRepositoryInterface
{
    // === BASIC CRUD ===
    public function find(int $id);
    public function findOrFail(int $id);
    public function create(array $data);
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;

    // === LISTING QUERIES ===
    public function getPublishedListings(int $perPage = 20);
    public function getListingsWithFilters(array $filters, int $perPage = 20);
    public function getFilteredListings(array $filters = [], array $sorting = [], int $perPage = 15);


    public function getUserListings(int $userId, int $perPage = 20);
    public function getListingForDetail(int $id);

    // === POPULAR & RECENT ===
    public function getPopularListings(int $limit = 10);
    public function getRecentListings(int $limit = 10);
    public function getRecentlyActiveListings(int $days = 30, int $limit = 10);

    // === SEARCH ===
    public function searchListings(string $query, array $filters = [], int $perPage = 20);

    // === STATUS OPERATIONS ===
    public function publishListing(int $id): bool;
    public function deactivateListing(int $id): bool;
    public function markAsCompleted(int $id): bool;

    // === STATISTICS ===
    public function getListingStats(int $id): array;
    public function getUserListingStats(int $userId): array;

    // === RELATIONSHIPS ===
    public function getListingWithImages(int $id);
    public function getListingWithAllRelations(int $id);
    public function getListingWithOffersForUser(int $listingId, int $userId, bool $isOwner = false): ?\App\Models\RepairListing; // YENİ EKLENEN

    // === BULK OPERATIONS ===
    public function bulkUpdateStatus(array $ids, string $status): int;
    public function deleteUserListings(int $userId): int;
}
