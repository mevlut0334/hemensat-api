<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Builder; // Scope kullanımı için eklendi
use Illuminate\Support\Facades\Log; // boot metodu için kullanıldı

class RepairListing extends Model
{
    use HasFactory, SoftDeletes;

    // Varsayılan tablo adını kullandığınız için (repair_listings) bu satır opsiyoneldir,
    // ancak açıkça belirtilmesi bir sorun teşkil etmez.
    // protected $table = "repair_listings";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "user_id",
        "title",
        "description",
        "brand_id",
        "model_id", // Foreign key model_id olarak kalmalı
        'phone',
        "storage_capacity_id",
        "purchase_source_id",
        "province_id",
        "district_id",
        "is_urgent",
        "preferred_repair_type",
        "status",
        "published_at",
        "expires_at",
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        "is_urgent" => "boolean",
        "published_at" => "datetime",
        "expires_at" => "datetime",
        "status" => "string",
    ];

    // --------------------------------------------------------------------------
    // İLİŞKİLER (RELATIONSHIPS)
    // --------------------------------------------------------------------------

    /**
     * İlanın sahibi olan kullanıcı.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Tamir edilecek cihazın markası.
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Tamir edilecek cihazın modeli.
     * model_id foreign key'ini kullanır ve DeviceModel modeline bağlanır.
     * Bu, migration'daki model_id kolonuna uyar.
     */
    public function deviceModel(): BelongsTo
    {
        // 'model_id' yabancı anahtarı, 'DeviceModel' modelinin 'id' kolonuna bağlanır.
        return $this->belongsTo(DeviceModel::class, 'model_id');
    }

    /**
     * Cihazın depolama kapasitesi (opsiyonel).
     */
    public function storageCapacity(): BelongsTo
    {
        return $this->belongsTo(StorageCapacity::class);
    }

    /**
     * Cihazın satın alma kaynağı (opsiyonel).
     */
    public function purchaseSource(): BelongsTo
    {
        return $this->belongsTo(PurchaseSource::class);
    }

    /**
     * İlanın yayınlandığı il.
     */
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    /**
     * İlanın yayınlandığı ilçe.
     */
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    /**
     * İlanla ilişkili resimler (Polymorphic).
     */
    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function offers()
{
    return $this->morphMany(Offer::class, 'offerable');
}

    // --------------------------------------------------------------------------
    // SCOPE'LAR ve DURUM METOTLARI
    // --------------------------------------------------------------------------

    /**
     * Sadece aktif ilanları getirir.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where("status", "active");
    }

    /**
     * Liste sayfasında gösterilecek temel ilişkileri yükler.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeForListingPage(Builder $query): Builder
    {
        return $query->with(["brand", "deviceModel", "province", "district"]);
    }

    /**
     * Detay sayfasında gösterilecek tüm ilişkileri yükler.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeForDetailPage(Builder $query): Builder
    {
        return $query->with([
            "brand",
            // "model_id" kaldırıldı. İlişki metodu "deviceModel" kullanılmalı.
            "deviceModel",
            "storageCapacity",
            "purchaseSource",
            "province",
            "district",
            "images",
            'phone'
        ]);
    }

    /**
     * Konuma göre filtreleme yapar.
     *
     * @param Builder $query
     * @param int|null $provinceId
     * @param int|null $districtId
     * @return Builder
     */
    public function scopeByLocation(Builder $query, ?int $provinceId = null, ?int $districtId = null): Builder
    {
        if ($provinceId) {
            $query->where("province_id", $provinceId);
        }

        if ($districtId) {
            $query->where("district_id", $districtId);
        }

        return $query;
    }

    /**
     * Belirtilen kullanıcıya ait ilanları getirir.
     *
     * @param Builder $query
     * @param int $userId
     * @return Builder
     */
    public function scopeByUser(Builder $query, int $userId): Builder
    {
        return $query->where("user_id", $userId);
    }

    /**
 * Belirtilen markaya ait ilanları getirir.
 *
 * @param Builder $query
 * @param int $brandId
 * @return Builder
 */
public function scopeByBrand(Builder $query, int $brandId): Builder
{
    return $query->where("brand_id", $brandId);
}

/**
 * Belirtilen modele ait ilanları getirir.
 *
 * @param Builder $query
 * @param int $modelId
 * @return Builder
 */
public function scopeByModel(Builder $query, int $modelId): Builder
{
    return $query->where("model_id", $modelId);
}

/**
 * Birden fazla filtreyi aynı anda uygular.
 *
 * @param Builder $query
 * @param array $filters
 * @return Builder
 */
public function scopeApplyFilters(Builder $query, array $filters): Builder
{
    // Brand filter
    if (!empty($filters['brand_id'])) {
        $query->byBrand($filters['brand_id']);
    }

    // Model filter
    if (!empty($filters['model_id'])) {
        $query->byModel($filters['model_id']);
    }

    // Location filters
    if (!empty($filters['province_id']) || !empty($filters['district_id'])) {
        $query->byLocation(
            $filters['province_id'] ?? null,
            $filters['district_id'] ?? null
        );
    }

    // Status filter (eğer admin panelinde kullanılacaksa)
    if (!empty($filters['status'])) {
        $query->where('status', $filters['status']);
    }

    return $query;
}

/**
 * Sıralama uygular.
 *
 * @param Builder $query
 * @param string $sortBy
 * @param string $direction
 * @return Builder
 */
public function scopeApplySorting(Builder $query, string $sortBy = 'created_at', string $direction = 'desc'): Builder
{
    $allowedSorts = [
        'created_at',
        'updated_at',
        'published_at',
        'total_offers_count',
        'last_offer_at'
    ];

    if (in_array($sortBy, $allowedSorts)) {
        $query->orderBy($sortBy, $direction);
    } else {
        $query->orderBy('created_at', 'desc');
    }

    return $query;
}

    /**
     * İlanın durumunu aktif olarak günceller.
     */
    public function publish(): void
    {
        $this->status = "active";
        $this->published_at = $this->published_at ?? now(); // İlk yayınlanma zamanını ayarlar
        $this->save();
    }

    public function deleteListing(int $id): bool
{
    $listing = RepairListing::find($id);

    if (! $listing) return false;

    $listing->status = 'deleted';
    $listing->saveQuietly();

    return $listing->delete(); // soft delete
}


    /**
     * İlanın durumunu inaktif olarak günceller.
     */
    public function deactivate(): void
    {
        $this->status = "inactive";
        $this->save();
    }

    /**
     * İlanın durumunu tamamlandı olarak günceller.
     */
    public function markAsCompleted(): void
    {
        $this->status = "completed";
        $this->save();
    }




}
