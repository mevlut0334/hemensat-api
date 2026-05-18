<?php

namespace App\Observers;

use App\Jobs\SendDistrictNotification;
use App\Models\RepairListing;

class RepairListingObserver
{
    public function created(RepairListing $repairListing): void
    {
        SendDistrictNotification::dispatch(
            districtId: $repairListing->district_id,
            title:      '🔧 Yeni Tamir İlanı!',
            body:       $repairListing->title ?? 'Bölgenizde yeni bir telefon tamir ilanı eklendi.',
            data:       [
                'type'       => 'repair_listing',
                'listing_id' => (string) $repairListing->id,
            ]
        );
    }
}
