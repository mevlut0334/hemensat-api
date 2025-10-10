<?php

namespace Database\Seeders\Location;

use App\Models\District;
use App\Models\Province;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //$json = File::get(database_path('seeders/data/location/districts.json'));
         // ✅ Linux uyumlu path (Data/Location büyük harfle)
        $json = File::get(database_path('seeders/Data/Location/districts.json'));
        $data = json_decode($json, true);

        foreach ($data as $districtData) {
            // İl ismine göre veritabanından ilin gerçek ID'sini bul
            // Bu kısım doğru çalışıyor ve province_name'e göre province objesini buluyor.
            $province = Province::where('name', $districtData['province_name'])->first();

            // Eğer il bulunduysa, ilçeyi ekle
            if ($province) {
                // firstOrCreate metodu iki parametre alır:
                // 1. Benzersizlik kontrolü (find conditions)
                // 2. Yaratılacak veriler (creation attributes)
                  // 🚨 KRİTİK LOG KAYDI: Kaydedilecek veriyi görme


                // 🚨 GÜNCELLEME: Benzersizlik kontrolü için sadece 'name' ve 'province_id' kullanılıyor.
                // JSON'daki hatalı/tekrarlanan 'id' alanı tamamen yok sayılıyor.
                District::firstOrCreate([
                    'name' => $districtData['name'],
                    'province_id' => $province->id // Doğru il ID'si ile eşleştirme
                ], [
                    'name' => $districtData['name'],
                    'province_id' => $province->id
                ]);
            }
        }
    }
}
