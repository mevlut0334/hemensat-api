<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\SaleListing;
use App\Models\RepairListing;
use Illuminate\Support\Facades\Log;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    $cutoff = now()->subHours(48);

    // --- SaleListing ---
    SaleListing::withTrashed()
        ->where('created_at', '<', $cutoff)
        ->each(function ($listing) {
            $listing->offers()->forceDelete();
            $listing->images()->each(fn($img) => $img->delete());
            $listing->forceDelete();
        });

    // --- RepairListing ---
    RepairListing::withTrashed()
        ->where('created_at', '<', $cutoff)
        ->each(function ($listing) {
            $listing->offers()->forceDelete();
            $listing->images()->each(fn($img) => $img->delete());
            $listing->forceDelete();
        });

    Log::info('Süresi dolan ilanlar silindi: ' . now());

})->hourly()->name('delete-expired-listings');
