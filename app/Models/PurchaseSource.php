<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseSource extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'purchase_sources';

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
     * Scope a query to only include active purchase sources.
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Check if purchase source is from Turkey.
     */
    public function isTurkishSource()
    {
        return $this->id === 1; // "Türkiye'den Alındı"
    }

    /**
     * Check if purchase source is from abroad.
     */
    public function isForeignSource()
    {
        return in_array($this->id, [2, 3, 4]); // Yurtdışından alınan seçenekler
    }

    /**
     * Check if passport record exists.
     */
    public function hasPassportRecord()
    {
        return in_array($this->id, [2, 3]); // Pasaport kaydı olan seçenekler
    }

    /**
     * Get customs tax status.
     */
    public function getCustomsTaxStatus()
    {
        switch ($this->id) {
            case 1:
                return 'no_tax'; // Türkiye'den - vergi yok
            case 2:
                return 'may_require'; // 3 yıldan az - vergi gerekebilir
            case 3:
                return 'may_require'; // 3 yıldan fazla - vergi gerekebilir
            case 4:
                return 'unknown'; // Kayıt yok - belirsiz
            default:
                return 'unknown';
        }
    }
}
