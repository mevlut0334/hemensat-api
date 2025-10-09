<?php

namespace App\Providers;

use App\Contracts\AuthRepositoryInterface;
use App\Contracts\AuthServiceInterface;
use App\Repositories\AuthRepository;
use App\Services\AuthService;
// DOĞRU import - AuthServiceProvider'ı extend etmeliyiz
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Contracts\ProvinceServiceInterface;
use App\Services\ProvinceService;
use App\Contracts\DistrictServiceInterface;
use App\Services\DistrictService;
use App\Contracts\ProvinceRepositoryInterface;
use App\Repositories\ProvinceRepository;
use App\Contracts\DistrictRepositoryInterface;
use App\Repositories\DistrictRepository;
use App\Models\SaleListing;
use App\Policies\SaleListingPolicy;
use Illuminate\Support\Facades\Gate;
use App\Models\RepairListing;
use App\Policies\RepairListingPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Uygulama için model-policy eşlemeleri.
     * Bu dizi sayesinde Laravel otomatik policy binding yapar
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        SaleListing::class => SaleListingPolicy::class,
        RepairListing::class => RepairListingPolicy::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Interface'leri somut sınıflarına bağlıyoruz.
        $this->app->bind(AuthServiceInterface::class, AuthService::class);
        $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);

        // İl ve ilçe servislerinin binding işlemi
        $this->app->bind(ProvinceServiceInterface::class, ProvinceService::class);
        $this->app->bind(DistrictServiceInterface::class, DistrictService::class);

        // Repository binding'leri
        $this->app->bind(ProvinceRepositoryInterface::class, ProvinceRepository::class);
        $this->app->bind(DistrictRepositoryInterface::class, DistrictRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Policy'leri otomatik kaydetmek için registerPolicies() çağır
        $this->registerPolicies();

        // Custom Gate'ler

        // İlan detay görüntüleme gate'i
        Gate::define('view-listing-details', function ($user, $listing) {
            // Kendi ilanını her zaman görebilir
            if ($listing->user_id === $user->id) {
                return true;
            }

            // Başkasının ilanını görmek için abone olmalı
            return $user->isSubscriber();
        });

        // Teklif verme gate'i
        Gate::define('make-offer', function ($user, $listing) {
            // Kendi ilanına teklif veremez
            if ($listing->user_id === $user->id) {
                return false;
            }

            // Teklif vermek için abone olmalı
            if (!$user->isSubscriber()) {
                return false;
            }

            // İlan aktif olmalı
            if ($listing->status !== 'active') {
                return false;
            }

            return true;
        });

        // Teklif görüntüleme gate'i
        Gate::define('view-offers', function ($user, $listing) {
            // Sadece ilan sahibi kendi ilanının tekliflerini görebilir
            return $listing->user_id === $user->id;
        });

        // Teklif yönetimi gate'i (kabul/red)
        Gate::define('manage-offers', function ($user, $listing) {
            // Sadece ilan sahibi kendi ilanının tekliflerini yönetebilir
            return $listing->user_id === $user->id;
        });

        // Log test için
        //Log::info('AuthServiceProvider boot() çalıştı - Policy ve Gate kayıtları tamamlandı.');
    }
}
