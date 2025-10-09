<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'provinces';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'plate_code',
        'region',
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
     * Get the districts for the province.
     */
    public function districts()
    {
        return $this->hasMany(District::class, 'province_id');
    }

    /**
     * Get active districts for the province.
     */
    public function activeDistricts()
    {
        return $this->hasMany(District::class, 'province_id')->where('status', true);
    }

    /**
     * Scope a query to only include active provinces.
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope a query to filter by region.
     */
    public function scopeByRegion($query, $region)
    {
        if ($region) {
            return $query->where('region', $region);
        }

        return $query;
    }

    /**
     * Get province by plate code.
     */
    public function scopeByPlateCode($query, $plateCode)
    {
        return $query->where('plate_code', $plateCode);
    }
}
