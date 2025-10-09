<?php

namespace App\Repositories;

use App\Contracts\RepairListingRepositoryInterface;
use App\Models\RepairListing;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // Sadece hata logları için tutulur


class RepairListingRepository implements RepairListingRepositoryInterface
{
    protected $model;

    public function __construct(RepairListing $model)
    {
        $this->model = $model;
    }

    // === BASIC CRUD ===

    public function find(int $id): ?RepairListing
    {
        return $this->model->find($id);
    }

    public function findOrFail(int $id): RepairListing
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data): RepairListing
    {
        // ❌ Geliştirme logu kaldırıldı: '🏪 RepairListingRepository@create called'

        // Gizli alanları çıkar (extra önlem)
        unset($data['images'], $data['temp_image_paths']);

        // ❌ Geliştirme logu kaldırıldı: '🧹 Data after cleanup'

        $listing = new $this->model;

        // ❌ Geliştirme logu kaldırıldı: '🔧 About to fill and save model'
        $listing->fill($data);

        // ❌ Geliştirme logu kaldırıldı: '📝 Model filled, about to save'
        $listing->save(); // 👈 create() yerine save() kullan

        // ❌ Geliştirme logu kaldırıldı: '✅ Model saved successfully'

        return $listing;
    }

    public function update(int $id, array $data): bool
    {
        return $this->model->findOrFail($id)->update($data);
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

    DB::transaction(function () use ($listing) {
        // 1️⃣ Önce durumunu güncelle
        $listing->status = 'deleted';
        $listing->save();

        // 2️⃣ Sonra soft delete uygula
        $listing->delete();
    });

    return true;
}


    // === LISTING QUERIES ===

    public function getPublishedListings(int $perPage = 20): LengthAwarePaginator
    {
        return $this->model
            ->published()
            ->forListingPage()
            ->latest()
            ->paginate($perPage);
    }

    public function getListingsWithFilters(array $filters, int $perPage = 20): LengthAwarePaginator
    {
        // Bu metot, searchListings tarafından çağrılacaktır, ancak temel filtreleme mantığı burada
        $query = $this->model->published()->forListingPage();

        // Örnek filtre uygulama mantığı:
        if (isset($filters['brand_id'])) {
            $query->where('brand_id', $filters['brand_id']);
        }

        // Konum filtreleri
        $query->byLocation($filters['province_id'] ?? null, $filters['district_id'] ?? null);

        return $query->latest()->paginate($perPage);
    }

    /**
 * Get filtered listings with new filter system
 */
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

    // Return paginated results
    return $query->paginate($perPage);
}

    public function getUserListings(int $userId, int $perPage = 20): LengthAwarePaginator
    {
        return $this->model
            ->byUser($userId)
            ->latest()
            ->paginate($perPage);
    }

    public function getListingForDetail(int $id): ?RepairListing
    {
        // Modeldeki scopeForDetailPage'i kullanarak tüm detay ilişkilerini yükle
        return $this->model->forDetailPage()->find($id);
    }

    // === POPULAR & FEATURED ===

    public function getPopularListings(int $limit = 10): Collection
    {
        // Tamir ilanları için popülerlik tanımı: En çok görüntülenen veya en çok teklif alan.
        // Teklif olmadığı için, şimdilik en aktif ilanları alalım.
        return $this->model
            ->published()
            ->orderByDesc('updated_at')
            ->limit($limit)
            ->get();
    }

    public function getRecentListings(int $limit = 10): Collection
    {
        return $this->model
            ->published()
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function getRecentlyActiveListings(int $days = 30, int $limit = 10): Collection
    {
        // RepairListing'de 'last_offer_at' yok, bu yüzden son güncellenenleri alalım.
        return $this->model
            ->published()
            ->where('updated_at', '>', now()->subDays($days))
            ->orderByDesc('updated_at')
            ->limit($limit)
            ->get();
    }

    // === SEARCH ===

    public function searchListings(string $query, array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        $baseQuery = $this->model
            ->published()
            ->forListingPage()
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            });

        // Diğer filtreleri uygula
        $baseQuery->byLocation($filters['province_id'] ?? null, $filters['district_id'] ?? null);

        if (isset($filters['brand_id'])) {
            $baseQuery->where('brand_id', $filters['brand_id']);
        }

        // ... Diğer filtreler (model_id, storage_id vb.) buraya eklenebilir.

        return $baseQuery->latest()->paginate($perPage);
    }

    // === STATUS OPERATIONS ===

    public function deactivateListing(int $id): bool
    {
        $listing = $this->model->findOrFail($id);
        $listing->deactivate(); // Modeldeki deactivate metodu
        return true;
    }

    public function publishListing(int $id): bool
    {
        $listing = $this->model->findOrFail($id);
        $listing->publish(); // Modeldeki publish metodu
        return true;
    }

    public function markAsCompleted(int $id): bool
    {
        $listing = $this->model->findOrFail($id);
        $listing->markAsCompleted(); // Modeldeki markAsCompleted metodu
        return true;
    }

    // === STATISTICS ===

    public function getListingStats(int $id): array
    {
        // İlan bazlı istatistikler, örneğin görüntülenme sayısı
        return [
            'views' => 0, // Placeholder
            'days_active' => now()->diffInDays($this->model->findOrFail($id)->published_at),
        ];
    }

    public function getUserListingStats(int $userId): array
    {
        return [
            'total_listings' => $this->model->byUser($userId)->count(),
            'active_listings' => $this->model->byUser($userId)->where('status', 'active')->count(),
            'completed_listings' => $this->model->byUser($userId)->where('status', 'completed')->count(),
        ];
    }

    // === RELATIONSHIPS ===

    public function getListingWithImages(int $id): ?RepairListing
    {
        return $this->model->with('images')->find($id);
    }

   /**
 * Tüm ilişkileriyle birlikte ilan modelini döndürür
 * TEKLİFLER DAHİL DEĞİL - onlar ayrı kontrol edilir
 */
public function getListingWithAllRelations(int $id): ?RepairListing
{
    return $this->model
        ->with([
            'user',                    // İlan sahibi bilgileri
            'brand',                   // Marka bilgisi
            'deviceModel',             // Model bilgisi
            'storageCapacity',         // Depolama kapasitesi
            'purchaseSource',          // Satın alma kaynağı
            'province',                // İl bilgisi
            'district',                // İlçe bilgisi
            'images',                  // Tüm görseller
            // 'offers' - TEKLİFLER KALDIRILDI (ayrı kontrol edilecek)
        ])
        ->find($id);
}

/**
 * İlanın tekliflerini sadece yetkili kullanıcılar için döndürür
 * - İlan sahibi: Tüm teklifleri görebilir
 * - Teklif veren: Sadece kendi teklifini görebilir
 */
public function getListingWithOffersForUser(int $listingId, int $userId, bool $isOwner = false): ?RepairListing
{
    $query = $this->model->with([
        'user',
        'brand',
        'deviceModel',
        'storageCapacity',
        'purchaseSource',
        'province',
        'district',
        'images'
    ]);

    if ($isOwner) {
        // İlan sahibi - tüm teklifleri görebilir
        $query->with([
            'offers' => function($query) {
                $query->orderBy('created_at', 'desc');
            },
            'offers.user'
        ]);
    } else {
        // Sadece kullanıcının kendi tekliflerini görebilir
        $query->with([
            'offers' => function($query) use ($userId) {
                $query->where('user_id', $userId)
                      ->orderBy('created_at', 'desc');
            },
            'offers.user'
        ]);
    }

    return $query->find($listingId);
}

    // === BULK OPERATIONS ===

    public function bulkUpdateStatus(array $ids, string $status): int
    {
        return $this->model->whereIn('id', $ids)->update(['status' => $status]);
    }

    public function deleteUserListings(int $userId): int
    {
        // Soft Delete
        return $this->model->byUser($userId)->delete();
    }
}
