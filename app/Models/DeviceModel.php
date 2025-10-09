<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceModel extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'models';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'brand_id',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => 'boolean',
        'brand_id' => 'integer',
    ];

    /**
     * Disable timestamps if not needed
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Get the brand that owns the model.
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    /**
     * Scope a query to only include active models.
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope a query to filter by brand.
     */
    public function scopeByBrand($query, $brandId)
    {
        if ($brandId && $brandId != 0) {
            return $query->where('brand_id', $brandId);
        }

        return $query;
    }
}
