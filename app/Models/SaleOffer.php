<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// Model imports
use App\Models\SaleListing;
use App\Models\User;

class SaleOffer extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'sale_offers';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'sale_listing_id',
        'user_id',
        'offer_price',
        'status',
        'accepted_at',
        'rejected_at',
        'responded_by',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'sale_listing_id' => 'integer',
        'user_id' => 'integer',
        'responded_by' => 'integer',
        'offer_price' => 'decimal:2',
        'is_active' => 'boolean',
        'accepted_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    /**
     * Performance: Default eager loading relations
     */
    protected $with = ['user:id,name'];

    // === RELATIONSHIPS ===

    /**
     * Get the listing that the offer belongs to.
     */
    public function saleListing(): BelongsTo
    {
        return $this->belongsTo(SaleListing::class)->select([
            'id', 'title', 'user_id', 'brand_id', 'model_id',
            'status', 'created_at'
        ]);
    }

    /**
     * Get the user that made the offer.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->select(['id', 'name', 'email']);
    }

    /**
     * Get the user that responded to the offer.
     */
    public function respondedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responded_by')->select(['id', 'name']);
    }

    // === SCOPES ===

    /**
     * Scope a query to only include pending offers.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending')->where('is_active', true);
    }

    /**
     * Scope a query to only include accepted offers.
     */
    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    /**
     * Scope a query to only include rejected offers.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope a query to only include active offers.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to filter by listing.
     */
    public function scopeByListing($query, $listingId)
    {
        if ($listingId && $listingId != 0) {
            return $query->where('sale_listing_id', $listingId);
        }
        return $query;
    }

    /**
     * Scope a query to filter by user.
     */
    public function scopeByUser($query, $userId)
    {
        if ($userId && $userId != 0) {
            return $query->where('user_id', $userId);
        }
        return $query;
    }

    /**
     * Scope for optimized offer queries.
     */
    public function scopeWithBasicInfo($query)
    {
        return $query->select([
            'id', 'sale_listing_id', 'user_id', 'offer_price',
            'status', 'accepted_at', 'rejected_at', 'created_at'
        ]);
    }
}
