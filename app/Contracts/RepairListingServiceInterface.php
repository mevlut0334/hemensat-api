<?php

namespace App\Contracts;

interface RepairListingServiceInterface
{
    // === BASIC CRUD ===
    public function getListingById(int $id): ?array;
    public function getListingByIdOrFail(int $id): array;
    public function getListingModelById(int $id): ?\App\Models\RepairListing; // YENİ EKLENEN METOD
    public function createListing(array $data): \App\Models\RepairListing;
    public function updateListing(int $id, array $data): bool;
    public function deleteListing(int $id): bool;

    // === LISTING QUERIES ===
    public function getPublishedListings(int $perPage = 20): array;
    public function getListingsWithFilters(array $filters, int $perPage = 20): array;
    public function getFilteredListings(array $filters = [], array $sorting = [], int $perPage = 15): array;
    public function getUserListings(int $userId, int $perPage = 20): array;
    public function getListingForDetail(int $id): ?array;

    // === POPULAR & RECENT ===
    public function getPopularListings(int $limit = 10): array;
    public function getRecentListings(int $limit = 10): array;
    public function getRecentlyActiveListings(int $days = 30, int $limit = 10): array;

    // === SEARCH ===
    public function searchListings(string $query, array $filters = [], int $perPage = 20): array;

    // === STATUS OPERATIONS ===
    public function publishListing(int $id): bool;
    public function deactivateListing(int $id): bool;
    public function markAsCompleted(int $id): bool;

    // === STATISTICS ===
    public function getListingStats(int $id): array;
    public function getUserListingStats(int $userId): array;

    // === RELATIONSHIPS ===
    public function getListingWithImages(int $id): ?array;
    public function getListingWithAllRelations(int $id): ?\App\Models\RepairListing; // GÜNCELLENENE METOD

    public function getListingWithOffersForUser(int $listingId, int $userId, bool $isOwner = false): ?\App\Models\RepairListing; // YENİ EKLENEN

    // === BULK OPERATIONS ===
    public function bulkUpdateStatus(array $ids, string $status): int;
    public function deleteUserListings(int $userId): int;
}
