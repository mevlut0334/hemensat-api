<?php

namespace Database\Seeders\Location;

use App\Models\Province;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //$json = File::get(database_path('seeders/data/location/provinces.json'));
        // ✅ Linux uyumlu path (Data/Location büyük harfle)
        $json = File::get(database_path('seeders/Data/Location/provinces.json'));
        $data = json_decode($json, true);

        foreach ($data as $province) {
            Province::firstOrCreate([
                'id' => $province['id']
            ], $province);
        }
    }
}
