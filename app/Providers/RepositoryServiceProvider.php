<?php


namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Contracts\ImageRepositoryInterface;
use App\Contracts\ImageServiceInterface;
use App\Contracts\SaleListingRepositoryInterface;
use App\Contracts\SaleListingServiceInterface;
use App\Repositories\ImageRepository;
use App\Repositories\SaleListingRepository;
use App\Services\ImageService;
use App\Services\SaleListingService;
use App\Contracts\RepairListingRepositoryInterface;
use App\Repositories\RepairListingRepository;
use App\Contracts\RepairListingServiceInterface;
use App\Services\RepairListingService;
use App\Contracts\OfferRepositoryInterface;
use App\Contracts\OfferServiceInterface;
use App\Repositories\OfferRepository;
use App\Services\OfferService;
use App\Contracts\SubscriptionRepositoryInterface;
use App\Contracts\SubscriptionServiceInterface;
use App\Repositories\SubscriptionRepository;
use App\Services\SubscriptionService;





class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Repository Bindings
        $this->app->bind(SaleListingRepositoryInterface::class, SaleListingRepository::class);
        $this->app->bind(ImageRepositoryInterface::class, ImageRepository::class);
        $this->app->bind( RepairListingRepositoryInterface::class,RepairListingRepository::class);
        $this->app->bind(OfferRepositoryInterface::class, OfferRepository::class);
        $this->app->bind(SubscriptionRepositoryInterface::class, SubscriptionRepository::class);

        // Service Bindings
        $this->app->bind(SaleListingServiceInterface::class, SaleListingService::class);
        $this->app->bind(ImageServiceInterface::class, ImageService::class);
        $this->app->bind(RepairListingServiceInterface::class, RepairListingService::class);
        $this->app->bind(OfferServiceInterface::class, OfferService::class);
        $this->app->bind(SubscriptionServiceInterface::class, SubscriptionService::class);



    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
