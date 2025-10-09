<?php


namespace App\Services;

use App\Contracts\SaleListingRepositoryInterface;
use App\Contracts\SaleListingServiceInterface;
use App\Contracts\ImageServiceInterface;
use App\Models\SaleListing; // Model dosyasını buraya eklemeyi unutma
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Resources\SaleListingResource;


class SaleListingService implements SaleListingServiceInterface
{
    protected $listingRepository;
    protected $imageService;

    public function __construct(
        SaleListingRepositoryInterface $listingRepository,
        ImageServiceInterface $imageService
    ) {
        $this->listingRepository = $listingRepository;
        $this->imageService = $imageService;
    }

    // === BASIC CRUD ===
    public function getListingById(int $id): ?array
    {
        $listing = $this->listingRepository->find($id);
        return $listing ? $listing->toArray() : null;
    }

    public function getListingByIdOrFail(int $id): array
    {
        return $this->listingRepository->findOrFail($id)->toArray();
    }

    /**
     * Yeni ilan oluşturur ve Eloquent model nesnesini döndürür.
     */
    public function createListing(array $data): SaleListing // Dizi yerine SaleListing modelini döndürecek
    {
       //  Log::info("🔧 SaleListingService@createListing called, creating listing...");
    // SADECE İLAN OLUŞTURMA İŞLEMİNİ YAPMALIYIZ. RESİM İŞLEME KALDIRILDI.
    return DB::transaction(function () use ($data) {
        // Bu metod sadece SaleListing modelini oluşturup döndürür.
        // Resimlerle ilgili her şey Controller'da Job'a atılmalıdır.
        $listing = $this->listingRepository->create($data);

       // Log::info("✅ Sale Listing created by Repository: " . $listing->id);

        return $listing;
    });
    }

    public function updateListing(int $id, array $data): bool
    {
      return DB::transaction(function () use ($id, $data) {
        // Update listing
        $updated = $this->listingRepository->update($id, $data);

        // HATA BURADA DA VARDI, GÜNCELLEME İŞLEMİNDEN DE KALDIRILMALI
        // if (!empty($data["images"])) {
        //     $this->imageService->processListingImages($id, $data["images"]);
        // }

        return $updated;
    });
    }

    public function deleteListing(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            // Delete all related images
            $this->imageService->deleteListingImages($id);

            // Delete the listing
            return $this->listingRepository->delete($id);
        });
    }

    // === LISTING QUERIES ===
    public function getPublishedListings(int $perPage = 20): array
    {
        $cacheKey = "published_listings_page_" . request()->get("page", 1) . "_per_page_{$perPage}";

        return Cache::remember($cacheKey, 300, function () use ($perPage) {
            $paginator = $this->listingRepository->getPublishedListings($perPage);
            return $this->convertPaginatorToArray($paginator);
        });
    }

   /**
 * Get filtered sale listings
 */
public function getFilteredListings(array $filters = [], array $sorting = [], int $perPage = 15)
{
    $listings = $this->listingRepository->getFilteredListings($filters, $sorting, $perPage);

    return [
        'data' => SaleListingResource::collection($listings),
        'meta' => [
            'current_page' => $listings->currentPage(),
            'last_page' => $listings->lastPage(),
            'per_page' => $listings->perPage(),
            'total' => $listings->total(),
            'from' => $listings->firstItem(),
            'to' => $listings->lastItem(),
        ],
        'links' => [
            'first' => $listings->url(1),
            'last' => $listings->url($listings->lastPage()),
            'prev' => $listings->previousPageUrl(),
            'next' => $listings->nextPageUrl(),
        ],
    ];
}

    public function getListingsWithFilters(array $filters, int $perPage = 20): array
    {
        // Create a unique cache key based on filters and pagination
        $filterHash = md5(json_encode($filters));
        $page = request()->get("page", 1);
        $cacheKey = "filtered_listings_{$filterHash}_page_{$page}_per_page_{$perPage}";

        return Cache::remember($cacheKey, 180, function () use ($filters, $perPage) {
            $paginator = $this->listingRepository->getListingsWithFilters($filters, $perPage);
            return $this->convertPaginatorToArray($paginator);
        });
    }

    public function getUserListings(int $userId, int $perPage = 20): array
    {
        $page = request()->get("page", 1);
        $cacheKey = "user_listings_{$userId}_page_{$page}_per_page_{$perPage}";

        return Cache::remember($cacheKey, 180, function () use ($userId, $perPage) {
            $paginator = $this->listingRepository->getUserListings($userId, $perPage);
            return $this->convertPaginatorToArray($paginator);
        });
    }

    public function getListingForDetail(int $id): ?array
    {
        $cacheKey = "listing_detail_{$id}";

        return Cache::remember($cacheKey, 300, function () use ($id) {
            $listing = $this->listingRepository->getListingForDetail($id);
            return $listing ? $listing->toArray() : null;
        });
    }

    // === POPULAR & FEATURED ===
    public function getPopularListings(int $limit = 10): array
    {
        return $this->listingRepository->getPopularListings($limit)->toArray();
    }

    public function getRecentListings(int $limit = 10): array
    {
        return $this->listingRepository->getRecentListings($limit)->toArray();
    }

    public function getRecentlyActiveListings(int $days = 30, int $limit = 10): array
    {
        $cacheKey = "recently_active_listings_{$days}_{$limit}";

        return Cache::remember($cacheKey, 180, function () use ($days, $limit) {
            return $this->listingRepository->getRecentlyActiveListings($days, $limit)->toArray();
        });
    }

    // === SEARCH ===
    public function searchListings(string $query, array $filters = [], int $perPage = 20): array
    {
        // Create a unique cache key based on search query and filters
        $filterHash = md5(json_encode($filters));
        $queryHash = md5($query);
        $page = request()->get("page", 1);
        $cacheKey = "search_listings_{$queryHash}_{$filterHash}_page_{$page}_per_page_{$perPage}";

        return Cache::remember($cacheKey, 120, function () use ($query, $filters, $perPage) {
            $paginator = $this->listingRepository->searchListings($query, $filters, $perPage);
            return $this->convertPaginatorToArray($paginator);
        });
    }

    // === STATUS OPERATIONS ===
    public function publishListing(int $id): bool
    {
        $result = $this->listingRepository->publishListing($id);

        if ($result) {
            // Clear related caches
            $this->clearListingCaches($id);
        }

        return $result;
    }

    public function deactivateListing(int $id): bool
    {
        $result = $this->listingRepository->deactivateListing($id);

        if ($result) {
            // Clear related caches
            $this->clearListingCaches($id);
        }

        return $result;
    }

    public function markAsSold(int $id): bool
    {
        $result = $this->listingRepository->markAsSold($id);

        if ($result) {
            // Clear related caches
            $this->clearListingCaches($id);
        }

        return $result;
    }

    // === STATISTICS ===
    public function getListingStats(int $id): array
    {
        $cacheKey = "listing_stats_{$id}";

        return Cache::remember($cacheKey, 300, function () use ($id) {
            return $this->listingRepository->getListingStats($id);
        });
    }

    public function getUserListingStats(int $userId): array
    {
        $cacheKey = "user_listing_stats_{$userId}";

        return Cache::remember($cacheKey, 360, function () use ($userId) {
            return $this->listingRepository->getUserListingStats($userId);
        });
    }

    // === RELATIONSHIPS ===
    public function getListingWithImages(int $id): ?array
    {
        $cacheKey = "listing_with_images_{$id}";

        return Cache::remember($cacheKey, 300, function () use ($id) {
            $listing = $this->listingRepository->getListingWithImages($id);
            return $listing ? $listing->toArray() : null;
        });
    }

    public function getListingWithOffers(int $id): ?array
    {
        $cacheKey = "listing_with_offers_{$id}";

        return Cache::remember($cacheKey, 60, function () use ($id) {
            $listing = $this->listingRepository->getListingWithOffers($id);
            return $listing ? $listing->toArray() : null;
        });
    }

    public function getListingWithAllRelations(int $id): ?array
    {
        $cacheKey = "listing_with_all_relations_{$id}";

        return Cache::remember($cacheKey, 300, function () use ($id) {
            $listing = $this->listingRepository->getListingWithAllRelations($id);
            return $listing ? $listing->toArray() : null;
        });
    }

    // === BULK OPERATIONS ===
    public function bulkUpdateStatus(array $ids, string $status): int
    {
        $result = $this->listingRepository->bulkUpdateStatus($ids, $status);

        if ($result > 0) {
            // Clear caches for all affected listings
            foreach ($ids as $id) {
                $this->clearListingCaches($id);
            }
        }

        return $result;
    }

    public function deleteUserListings(int $userId): int
    {
        return DB::transaction(function () use ($userId) {
            // Get all user listings
            $listings = $this->listingRepository->getUserListings($userId, 1000);
            $listingIds = [];

            // Convert paginator to array of IDs
            foreach ($listings as $listing) {
                $listingIds[] = $listing->id;
            }

            // Delete images for all listings
            foreach ($listingIds as $listingId) {
                $this->imageService->deleteListingImages($listingId);
            }

            // Delete all listings
            return $this->listingRepository->deleteUserListings($userId);
        });
    }

    // === PRIVATE HELPER METHODS ===
    private function clearListingCaches(int $listingId): void
    {
        // Clear all caches related to this listing
        $cacheKeys = [
            "listing_detail_{$listingId}",
            "listing_with_images_{$listingId}",
            "listing_with_offers_{$listingId}",
            "listing_with_all_relations_{$listingId}",
            "listing_stats_{$listingId}",
        ];

        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }

        // Clear general listings caches that might contain this listing
        Cache::forget("popular_listings_10");
        Cache::forget("recent_listings_10");
        Cache::forget("recently_active_listings_30_10");
    }

    /**
     * Convert paginator to array with proper structure
     *
     * @param LengthAwarePaginator $paginator
     * @return array
     */
    private function convertPaginatorToArray(LengthAwarePaginator $paginator): array
    {
        return [
            "data" => $paginator->items(),
            "pagination" => [
                "total" => $paginator->total(),
                "count" => $paginator->count(),
                "per_page" => $paginator->perPage(),
                "current_page" => $paginator->currentPage(),
                "total_pages" => $paginator->lastPage(),
                "has_more_pages" => $paginator->hasMorePages(),
            ]
        ];
    }
}
