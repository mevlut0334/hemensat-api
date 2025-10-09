<?php

namespace Database\Seeders\Device;

use Illuminate\Database\Seeder;
use App\Models\Brand;
use Illuminate\Support\Facades\File;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // JSON dosyasının yolunu belirle
        $jsonPath = database_path('seeders/Data/Device/brands.json');

        // JSON dosyasını kontrol et
        if (!File::exists($jsonPath)) {
            $this->command->error("brands.json dosyası bulunamadı: {$jsonPath}");
            return;
        }

        // JSON dosyasını oku
        $jsonContent = File::get($jsonPath);
        $brands = json_decode($jsonContent, true);

        // JSON decode kontrolü
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->command->error('brands.json dosyası geçersiz JSON formatında');
            return;
        }

        // Mevcut kayıtları temizle (Foreign key constraint nedeniyle delete kullan)
        Brand::query()->delete();

        // Brands tablosunu doldur
        foreach ($brands as $brand) {
            Brand::create([
                'id' => $brand['id'],
                'name' => $brand['name'],
                'status' => $brand['status'],
            ]);
        }

        $this->command->info('Brands tablosu başarıyla dolduruldu: ' . count($brands) . ' kayıt eklendi');
    }
}
