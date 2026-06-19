<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\SaleListing;
use App\Models\UserFcmToken;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "name",
        "email",
        "password",
        "province_id",
        "district_id",
        'is_subscribed'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        "password",
        "remember_token",
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        "email_verified_at" => "datetime",
    ];

    /**
     * Get the province associated with the user.
     */
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    /**
     * Get the district associated with the user.
     */
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    /**
     * Kullanıcının ilan ilişkisi
     */
    public function saleListings(): HasMany
    {
        return $this->hasMany(SaleListing::class, "user_id");
    }

    /**
     * Kullanıcının tamir ilanı ilişkisi
     */
    public function repairListings(): HasMany
    {
        return $this->hasMany(RepairListing::class, "user_id");
    }

    /**
     * Kullanıcının verdiği teklifler
     */
    public function offers(): HasMany
    {
        return $this->hasMany(Offer::class, "user_id");
    }

    /**
     * Kullanıcının yeni bir satış ilanı oluşturması
     *
     * @param array $data İlan verileri (title, description, brand_id vs.)
     * @param array $images Yüklenen resimler (opsiyonel)
     * @return SaleListing
     */
    public function createSaleListing(array $data, array $images = []): SaleListing
    {
        // İlanı oluştur
        $listing = $this->saleListings()->create($data);

        // Resim varsa ileride işlenecek (şimdilik log atalım)
        if (!empty($images)) {
            //\Log::info("Resimler alındı, ilan ID: " . $listing->id . ", adet: " . count($images));
            // \App\Jobs\ProcessImageUpload::dispatch($listing, $images); // Job hazır değilse şimdilik yorumda
        }

        return $listing;
    }

    /**
     * Check if user is a subscriber
     *
     * @return bool
     */
    public function isSubscriber(): bool
{
    // Daha önce konuştuğumuz gibi: is_subscribed sütununu kontrol ediyoruz.
    // Eğer veritabanında 'is_subscribed' sütununuz varsa, doğru kontrol budur.
    return (bool) $this->is_subscribed;
}
public function fcmTokens(): HasMany
    {
        return $this->hasMany(UserFcmToken::class);
    }
}
