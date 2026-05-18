<?php

namespace App\Observers;

use App\Jobs\SendDistrictNotification;
use App\Models\SaleListing;

class SaleListingObserver
{
    public function created(SaleListing $saleListing): void
    {
        SendDistrictNotification::dispatch(
            districtId: $saleListing->district_id,
            title:      '📱 Yeni Satılık İlan!',
            body:       $saleListing->title ?? 'Bölgenizde yeni bir satılık telefon ilanı eklendi.',
            data:       [
                'type'       => 'sale_listing',
                'listing_id' => (string) $saleListing->id,
            ]
        );
    }
}
