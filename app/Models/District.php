<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'districts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'province_id',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => 'boolean',
        'province_id' => 'integer',
    ];

    /**
     * Disable timestamps if not needed
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Get the province that owns the district.
     */
    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    /**
     * Scope a query to only include active districts.
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope a query to filter by province.
     */
    public function scopeByProvince($query, $provinceId)
    {
        if ($provinceId && $provinceId != 0) {
            return $query->where('province_id', $provinceId);
        }

        return $query;
    }

    /**
     * Get district with province information.
     */
    public function scopeWithProvince($query)
    {
        return $query->with('province');
    }
}
