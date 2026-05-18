<?php

namespace App\Providers;

use App\Models\RepairListing;
use App\Models\SaleListing;
use App\Observers\RepairListingObserver;
use App\Observers\SaleListingObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        SaleListing::observe(SaleListingObserver::class);
        RepairListing::observe(RepairListingObserver::class);
    }
}
