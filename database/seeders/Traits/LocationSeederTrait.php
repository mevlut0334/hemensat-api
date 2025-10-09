<?php

namespace Database\Seeders\Traits;

use Database\Seeders\Location\DistrictSeeder;
use Database\Seeders\Location\ProvinceSeeder;

trait LocationSeederTrait
{
    /**
     * Run the location database seeders.
     */
    protected function runLocationSeeders(): void
    {
        $this->call([
            ProvinceSeeder::class,
            DistrictSeeder::class,
        ]);
    }
}
