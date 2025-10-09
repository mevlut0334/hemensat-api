<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StorageCapacity extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'storage_capacities';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'capacity',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * Disable timestamps if not needed
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Scope a query to only include active storage capacities.
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope a query to order by capacity value.
     */
    public function scopeOrderByCapacity($query)
    {
        return $query->orderByRaw("
            CASE
                WHEN capacity = 'Tümü' THEN 0
                WHEN capacity LIKE '%GB' THEN CAST(REPLACE(capacity, 'GB', '') AS UNSIGNED)
                WHEN capacity LIKE '%TB' THEN CAST(REPLACE(capacity, 'TB', '') AS UNSIGNED) * 1024
                ELSE 9999
            END
        ");
    }

    /**
     * Get formatted capacity for display.
     */
    public function getFormattedCapacityAttribute()
    {
        return $this->capacity;
    }
}
