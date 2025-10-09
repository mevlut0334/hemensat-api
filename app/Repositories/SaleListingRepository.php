<?php


namespace App\Repositories;

use App\Contracts\SaleListingRepositoryInterface;
use App\Models\SaleListing;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class SaleListingRepository implements SaleListingRepositoryInterface
{
    protected $model;

    public function __construct(SaleListing $model)
    {
        $this->model = $model;
    }

    // === BASIC CRUD ===

    public function find(int $id): ?SaleListing
    {
        return $this->model->find($id);
    }

    public function findOrFail(int $id): SaleListing
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data): SaleListing
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        return $this->model->where('id', $id)->update($data);
    }

    public function delete(int $id): bool
    {
        $listing = $this->model->find($id);

        if (!$listing) {
            return false;
        }

        // Eğer zaten silinmişse tekrar işlem yapma
        if ($listing->status === 'deleted') {
            return true;
        }

        $listing->status = 'deleted';
        $listing->save();

        return true;
    }


    // === LISTING QUERIES ===

    public function getPublishedListings(int $perPage = 20): LengthAwarePaginator
    {
        return $this->model->published()
            ->forListingPage()
            ->with(['primaryImage', 'firstImage'])
            ->orderBy('published_at', 'desc')
            ->paginate($perPage);
    }

    public function getListingsWithFilters(array $filters, int $perPage = 20): LengthAwarePaginator
    {
        $query = $this->model->published()->forListingPage();

        // Brand filter
        if (!empty($filters['brand_id'])) {
            $query->byBrand($filters['brand_id']);
        }

        // Model filter
        if (!empty($filters['model_id'])) {
            $query->byModel($filters['model_id']);
        }

        // Storage filter
        if (!empty($filters['storage_id'])) {
            $query->byStorage($filters['storage_id']);
        }

        // Location filter
        if (!empty($filters['province_id']) || !empty($filters['district_id'])) {
            $query->byLocation($filters['province_id'] ?? null, $filters['district_id'] ?? null);
        }

        // Price range
        if (!empty($filters['min_price'])) {
            $query->where('highest_offer_price', '>=', $filters['min_price']);
        }

        if (!empty($filters['max_price'])) {
            $query->where('highest_offer_price', '<=', $filters['max_price']);
        }

        // Has offers filter
        if (!empty($filters['has_offers'])) {
            $query->hasOffers();
        }

        // Recently active filter
        if (!empty($filters['recent_activity'])) {
            $query->recentlyActive($filters['recent_activity']);
        }

        // Sorting
        $sortBy = $filters['sort_by'] ?? 'newest';
        switch ($sortBy) {
            case 'price_high':
                $query->orderBy('highest_offer_price', 'desc');
                break;
            case 'price_low':
                $query->orderBy('highest_offer_price', 'asc');
                break;
            case 'popular':
                $query->orderBy('total_offers_count', 'desc')
                    ->orderBy('last_offer_at', 'desc');
                break;
            case 'oldest':
                $query->orderBy('published_at', 'asc');
                break;
            default: // newest
                $query->orderBy('published_at', 'desc');
                break;
        }

        return $query->with(['primaryImage', 'firstImage'])
            ->paginate($perPage);
    }

    public function getFilteredListings(array $filters = [], array $sorting = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->published()->forListingPage();

        // Apply filters using the model's scope
        if (!empty($filters)) {
            $query->applyFilters($filters);
        }

        // Apply sorting
        $sortBy = $sorting['sort_by'] ?? 'created_at';
        $sortDirection = $sorting['sort_direction'] ?? 'desc';
        $query->applySorting($sortBy, $sortDirection);

        // Load necessary relationships
        return $query->with(['primaryImage', 'firstImage'])
            ->paginate($perPage);
    }

    public function getUserListings(int $userId, int $perPage = 20): LengthAwarePaginator
    {
        return $this->model->byUser($userId)
            ->where('status', 'active') // 👈 SADECE aktif ilanlar
            ->with([
                'primaryImage',
                'firstImage',
                'user',
                'brand',
                'deviceModel',
                'storageCapacity',
                'purchaseSource',
                'province',
                'district'
            ])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }



    public function getListingForDetail(int $id): ?SaleListing
    {
        return $this->model->forDetailPage()
            ->where('id', $id)
            ->first();
    }

    // === POPULAR & FEATURED ===

    public function getPopularListings(int $limit = 10): Collection
    {
        $cacheKey = "popular_listings_{$limit}";

        return Cache::remember($cacheKey, 300, function () use ($limit) {
            return $this->model->popular($limit);
        });
    }

    public function getRecentListings(int $limit = 10): Collection
    {
        $cacheKey = "recent_listings_{$limit}";

        return Cache::remember($cacheKey, 180, function () use ($limit) {
            return $this->model->recent($limit);
        });
    }

    public function getRecentlyActiveListings(int $days = 30, int $limit = 10): Collection
    {
        return $this->model->published()
            ->recentlyActive($days)
            ->forListingPage()
            ->with(['primaryImage', 'firstImage'])
            ->orderBy('last_offer_at', 'desc')
            ->limit($limit)
            ->get();
    }

    // === SEARCH ===

    public function searchListings(string $query, array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        $searchQuery = $this->model->published()
            ->forListingPage()
            ->where(function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                    ->orWhere('description', 'LIKE', "%{$query}%")
                    ->orWhereHas('brand', function ($brandQuery) use ($query) {
                        $brandQuery->where('name', 'LIKE', "%{$query}%");
                    })
                    ->orWhereHas('deviceModel', function ($modelQuery) use ($query) {
                        $modelQuery->where('name', 'LIKE', "%{$query}%");
                    });
            });

        // Apply additional filters if provided
        if (!empty($filters)) {
            $searchQuery = $this->applyFilters($searchQuery, $filters);
        }

        return $searchQuery->with(['primaryImage', 'firstImage'])
            ->orderBy('published_at', 'desc')
            ->paginate($perPage);
    }

    // === STATUS OPERATIONS ===

    public function publishListing(int $id): bool
    {
        $listing = $this->find($id);
        if ($listing && $listing->status === 'draft') {
            $listing->publish();
            return true;
        }
        return false;
    }

    public function deactivateListing(int $id): bool
    {
        $listing = $this->find($id);
        if ($listing && $listing->status === 'active') {
            $listing->deactivate();
            return true;
        }
        return false;
    }

    public function markAsSold(int $id): bool
    {
        $listing = $this->find($id);
        if ($listing && in_array($listing->status, ['active', 'inactive'])) {
            $listing->markAsSold();
            return true;
        }
        return false;
    }

    // === STATISTICS ===

    public function getListingStats(int $id): array
    {
        $listing = $this->find($id);
        if (!$listing) {
            return [];
        }

        return [
            'total_offers' => $listing->total_offers_count,
            'pending_offers' => $listing->pending_offers_count,
            'accepted_offers' => $listing->accepted_offers_count,
            'rejected_offers' => $listing->rejected_offers_count,
            'highest_offer' => $listing->highest_offer_price,
            'latest_offer' => $listing->latest_offer_price,
            'average_offer' => $listing->average_offer_price,
            'last_offer_at' => $listing->last_offer_at,
            'images_count' => $listing->images()->count(),
            'views_count' => 0, // Implement view tracking if needed
        ];
    }

    public function getUserListingStats(int $userId): array
    {
        $stats = $this->model->byUser($userId)
            ->selectRaw('
                COUNT(*) as total_listings,
                SUM(CASE WHEN status = "active" THEN 1 ELSE 0 END) as active_listings,
                SUM(CASE WHEN status = "sold" THEN 1 ELSE 0 END) as sold_listings,
                SUM(CASE WHEN status = "inactive" THEN 1 ELSE 0 END) as inactive_listings,
                SUM(total_offers_count) as total_offers_received,
                MAX(highest_offer_price) as highest_offer_received,
                AVG(total_offers_count) as avg_offers_per_listing
            ')
            ->first();

        return [
            'total_listings' => $stats->total_listings ?? 0,
            'active_listings' => $stats->active_listings ?? 0,
            'sold_listings' => $stats->sold_listings ?? 0,
            'inactive_listings' => $stats->inactive_listings ?? 0,
            'total_offers_received' => $stats->total_offers_received ?? 0,
            'highest_offer_received' => $stats->highest_offer_received ?? 0,
            'avg_offers_per_listing' => round($stats->avg_offers_per_listing ?? 0, 2),
        ];
    }

    // === RELATIONSHIPS ===

    public function getListingWithImages(int $id): ?SaleListing
    {
        return $this->model->with('images')->find($id);
    }

    public function getListingWithOffers(int $id): ?SaleListing
    {
        return $this->model->with(['offers.user:id,name,email'])->find($id);
    }

    public function getListingWithAllRelations(int $id): ?SaleListing
    {
        return $this->model->with([
            'user:id,name,email,created_at',
            'brand',
            'deviceModel',
            'storageCapacity',
            'purchaseSource',
            'province',
            'district',
            'images',
            'offers.user:id,name,email'
        ])->find($id);
    }

    // === BULK OPERATIONS ===

    public function bulkUpdateStatus(array $ids, string $status): int
    {
        $allowedStatuses = ['active', 'inactive', 'sold', 'deleted'];

        if (!in_array($status, $allowedStatuses)) {
            return 0;
        }

        $updateData = ['status' => $status];

        // If publishing, add published_at timestamp
        if ($status === 'active') {
            $updateData['published_at'] = now();
        }

        return $this->model->whereIn('id', $ids)->update($updateData);
    }

    public function deleteUserListings(int $userId): int
    {
        return $this->model->byUser($userId)->delete();
    }

    // === PRIVATE HELPER METHODS ===

    private function applyFilters($query, array $filters)
    {
        if (!empty($filters['brand_id'])) {
            $query->byBrand($filters['brand_id']);
        }

        if (!empty($filters['model_id'])) {
            $query->byModel($filters['model_id']);
        }

        if (!empty($filters['storage_id'])) {
            $query->byStorage($filters['storage_id']);
        }

        if (!empty($filters['province_id']) || !empty($filters['district_id'])) {
            $query->byLocation($filters['province_id'] ?? null, $filters['district_id'] ?? null);
        }

        return $query;
    }
}
