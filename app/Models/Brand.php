<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'brands';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
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
     * Get the models for the brand.
     */
    public function models()
    {
        return $this->hasMany(DeviceModel::class, 'brand_id');
    }

    /**
     * Get active models for the brand.
     */
    public function activeModels()
    {
        return $this->hasMany(DeviceModel::class, 'brand_id')->where('status', true);
    }

    /**
     * Scope a query to only include active brands.
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
}
