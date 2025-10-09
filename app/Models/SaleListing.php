<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleListing extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "sale_listings";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "user_id",
        "title",
        "description",
        "brand_id",
        "model_id",
        "storage_capacity_id",
        "purchase_source_id",
        "province_id",
        "district_id",
        'phone',
        "status",
        "published_at",
        "expires_at",
        "pending_offers_count",
        "accepted_offers_count",
        "rejected_offers_count",
        "total_offers_count",
        "highest_offer_price",
        "latest_offer_price",
        "average_offer_price",
        "last_offer_at",
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        "highest_offer_price" => "decimal:2",
        "latest_offer_price" => "decimal:2",
        "average_offer_price" => "decimal:2",
        "published_at" => "datetime",
        "expires_at" => "datetime",
        "last_offer_at" => "datetime",
        "pending_offers_count" => "integer",
        "accepted_offers_count" => "integer",
        "rejected_offers_count" => "integer",
        "total_offers_count" => "integer",
        "status" => "string",
    ];

    // === BASIC RELATIONSHIPS ===

    /**
     * Get the user that owns the sale listing.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the brand that owns the sale listing.
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Get the model that owns the sale listing.
     */
    public function deviceModel(): BelongsTo
    {
        return $this->belongsTo(DeviceModel::class, 'model_id');
    }

    /**
     * Get the storage capacity that owns the sale listing.
     */
    public function storageCapacity(): BelongsTo
    {
        return $this->belongsTo(StorageCapacity::class);
    }

    /**
     * Get the purchase source that owns the sale listing.
     */
    public function purchaseSource(): BelongsTo
    {
        return $this->belongsTo(PurchaseSource::class);
    }

    /**
     * Get the province that owns the sale listing.
     */
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    /**
     * Get the district that owns the sale listing.
     */
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    // === IMAGE RELATIONSHIPS ===

    /**
     * Get all images for the sale listing.
     */
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable')
                    ->where('status', 'active')
                    ->orderBy('id');
    }

    /**
     * Get the primary image for the sale listing.
     */
    public function primaryImage()
    {
        return $this->morphOne(Image::class, 'imageable')
                    ->where('is_primary', true)
                    ->where('status', 'active');
    }

    /**
     * Get the first image for the sale listing.
     */
    public function firstImage()
    {
        return $this->morphOne(Image::class, 'imageable')
                    ->where('status', 'active')
                    ->orderBy('id');
    }

    // === OFFER RELATIONSHIPS ===

    /**
     * Get all offers for the sale listing.
     */
    public function offers()
    {
        return $this->morphMany(Offer::class, 'offerable');
    }

    /**
     * Get active offers for the sale listing.
     */
    public function activeOffers()
    {
        return $this->morphMany(Offer::class, 'offerable')
                    ->where('status', 'pending');
    }

    /**
     * Get accepted offers for the sale listing.
     */
    public function acceptedOffers()
    {
        return $this->morphMany(Offer::class, 'offerable')
                    ->where('status', 'accepted');
    }

    // === SCOPES ===

    /**
     * Scope a query to only include published listings.
     */
    public function scopePublished($query)
    {
        return $query->where("status", "active");
    }

    /**
     * Scope a query to only include listings for the listing page.
     */
    public function scopeForListingPage($query)
    {
        return $query->with(["brand", "deviceModel", "storageCapacity", "province", "district", "primaryImage", "firstImage"]);
    }

    /**
     * Scope a query to only include listings for the detail page.
     */
    public function scopeForDetailPage($query)
    {
        return $query->with(["brand", "deviceModel", "storageCapacity", "purchaseSource", "province", "district", "images"]);
    }

    /**
     * Scope a query to only include listings by brand.
     */
    public function scopeByBrand($query, $brandId)
    {
        return $query->where("brand_id", $brandId);
    }

    /**
     * Scope a query to only include listings by model.
     */
    public function scopeByModel($query, $modelId)
    {
        return $query->where("model_id", $modelId);
    }

    /**
     * Scope a query to only include listings by storage.
     */
    public function scopeByStorage($query, $storageId)
    {
        return $query->where("storage_capacity_id", $storageId);
    }

    /**
     * Scope a query to only include listings by location.
     */
    public function scopeByLocation($query, $provinceId = null, $districtId = null)
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
     * Scope a query to only include listings by user.
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where("user_id", $userId);
    }

    /**
     * Scope a query to only include listings that have offers.
     */
    public function scopeHasOffers($query)
    {
        return $query->where("total_offers_count", ">", 0);
    }

    /**
     * Scope a query to only include recently active listings.
     */
    public function scopeRecentlyActive($query, $days = 30)
    {
        return $query->where("last_offer_at", ">", now()->subDays($days));
    }

    /**
 * Scope a query to only include listings by purchase source.
 */
public function scopeByPurchaseSource($query, $purchaseSourceId)
{
    return $query->where("purchase_source_id", $purchaseSourceId);
}

/**
 * Scope a query to filter by multiple conditions at once.
 */
public function scopeApplyFilters($query, array $filters)
{
    // Brand filter
    if (!empty($filters['brand_id'])) {
        $query->byBrand($filters['brand_id']);
    }

    // Model filter
    if (!empty($filters['model_id'])) {
        $query->byModel($filters['model_id']);
    }

    // Storage filter
    if (!empty($filters['storage_capacity_id'])) {
        $query->byStorage($filters['storage_capacity_id']);
    }

    // Purchase source filter
    if (!empty($filters['purchase_source_id'])) {
        $query->byPurchaseSource($filters['purchase_source_id']);
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
 * Scope a query to apply sorting.
 */
public function scopeApplySorting($query, string $sortBy = 'created_at', string $direction = 'desc')
{
    $allowedSorts = [
        'created_at',
        'updated_at',
        'published_at',
        'highest_offer_price',
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

    // === METHODS ===

    /**
     * Mark the listing as published.
     */
    public function publish()
    {
        $this->status = "active";
        $this->published_at = now();
        $this->save();
    }

    /**
     * Mark the listing as inactive.
     */
    public function deactivate()
    {
        $this->status = "inactive";
        $this->save();
    }

    /**
     * Mark the listing as sold.
     */
    public function markAsSold()
    {
        $this->status = "sold";
        $this->save();
    }

    /**
     * Check if the listing is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if the listing is published.
     */
    public function isPublished(): bool
    {
        return $this->status === 'active' &&
               $this->published_at &&
               $this->published_at <= now();
    }

    /**
     * Check if the listing is sold.
     */
    public function isSold(): bool
    {
        return $this->status === 'sold';
    }

    /**
     * Check if the listing can receive offers.
     */
    public function canReceiveOffers(): bool
    {
        return $this->isActive() && !$this->isSold();
    }
}
