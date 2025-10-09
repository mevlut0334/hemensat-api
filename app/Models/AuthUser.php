<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class AuthUser extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Bu modelin kullanacağı tablonun adını belirtiyoruz.
     */
    protected $table = 'users';

    /**
     * Toplu atama yapılabilir alanlar.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'province_id',
        'district_id'
    ];

    /**
     * Serileştirme sırasında gizlenecek alanlar.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Dönüştürülmesi gereken veri tipleri.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function toUser(): User
    {
        return User::find($this->id);
    }

    public function isSubscriber(): bool
{
    // is_subscribed sütununu kontrol et
    // Eğer sütun adınız farklıysa, burayı güncelleyin.
    return (bool) $this->is_subscribed;
}
}
