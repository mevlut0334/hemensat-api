<?php

namespace Database\Seeders\Device;

use Illuminate\Database\Seeder;
use App\Models\DeviceModel;
use Illuminate\Support\Facades\File;

class ModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // JSON dosyasının yolunu belirle
        $jsonPath = database_path('seeders/Data/Device/models.json');

        // JSON dosyasını kontrol et
        if (!File::exists($jsonPath)) {
            $this->command->error("models.json dosyası bulunamadı: {$jsonPath}");
            return;
        }

        // JSON dosyasını oku
        $jsonContent = File::get($jsonPath);
        $models = json_decode($jsonContent, true);

        // JSON decode kontrolü
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->command->error('models.json dosyası geçersiz JSON formatında');
            return;
        }

        // Mevcut kayıtları temizle (Foreign key constraint nedeniyle delete kullan)
        DeviceModel::query()->delete();

        // Models tablosunu doldur
        foreach ($models as $model) {
            DeviceModel::create([
                'id' => $model['id'],
                'name' => $model['name'],
                'brand_id' => $model['brand_id'],
                'status' => $model['status'],
            ]);
        }

        $this->command->info('Models tablosu başarıyla dolduruldu: ' . count($models) . ' kayıt eklendi');
    }
}
