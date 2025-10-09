<?php

namespace Database\Seeders\Device;

use Illuminate\Database\Seeder;
use App\Models\StorageCapacity;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class StorageCapacitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kısıtlamaları geçici olarak devre dışı bırak
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Mevcut verileri temizle
        StorageCapacity::truncate();

        // JSON dosyasının yolunu belirle
        $jsonPath = database_path('seeders/Data/Device/storage_capacities.json');

        // JSON dosyasını kontrol et
        if (!File::exists($jsonPath)) {
            $this->command->error("storage_capacities.json dosyası bulunamadı: {$jsonPath}");
            // Kısıtlamaları tekrar etkinleştir
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            return;
        }

        // JSON dosyasını oku
        $jsonContent = File::get($jsonPath);
        $storageCapacities = json_decode($jsonContent, true);

        // JSON decode kontrolü
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->command->error('storage_capacities.json dosyası geçersiz JSON formatında');
            // Kısıtlamaları tekrar etkinleştir
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            return;
        }

        // Storage Capacities tablosunu doldur
        foreach ($storageCapacities as $storage) {
            StorageCapacity::create([
                'id' => $storage['id'],
                'capacity' => $storage['capacity'],
                'status' => $storage['status'],
            ]);
        }

        $this->command->info('Storage Capacities tablosu başarıyla dolduruldu: ' . count($storageCapacities) . ' kayıt eklendi');

        // Kısıtlamaları tekrar etkinleştir
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
