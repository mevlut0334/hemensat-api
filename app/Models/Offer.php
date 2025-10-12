<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Offer extends Model
{
    /**
     * Mass assignable olan alanlar.
     * Bu alanlar, toplu atama (mass assignment) yoluyla doldurulabilir.
     */
    protected $fillable = [
        'user_id',
        'offer_price',
        'phone_number',
        'status',
        'offerable_type',
        'offerable_id',
        'accepted_at',
        'rejected_at',
    ];

    /**
     * Varsayılan olarak date/time formatına dönüştürülecek alanlar.
     */
    protected $casts = [
        'accepted_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    /**
     * Teklifin ait olduğu RepairListing veya SaleListing Model'ini döndürür.
     * Bu bir polimorfik ilişkidir.
     */
    public function offerable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Teklifi veren kullanıcıyı döndürür.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

