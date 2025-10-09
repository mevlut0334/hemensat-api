<?php

namespace Database\Seeders\Device;

use Illuminate\Database\Seeder;
use App\Models\PurchaseSource;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class PurchaseSourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kısıtlamaları geçici olarak devre dışı bırak
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Mevcut kayıtları temizle
        PurchaseSource::truncate();

        // JSON dosyasının yolunu belirle
        $jsonPath = database_path('seeders/Data/Device/purchase_sources.json');

        // JSON dosyasını kontrol et
        if (!File::exists($jsonPath)) {
            $this->command->error("purchase_sources.json dosyası bulunamadı: {$jsonPath}");
            // Kısıtlamaları tekrar etkinleştir
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            return;
        }

        // JSON dosyasını oku
        $jsonContent = File::get($jsonPath);
        $purchaseSources = json_decode($jsonContent, true);

        // JSON decode kontrolü
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->command->error('purchase_sources.json dosyası geçersiz JSON formatında');
            // Kısıtlamaları tekrar etkinleştir
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            return;
        }

        // Purchase Sources tablosunu doldur
        foreach ($purchaseSources as $source) {
            PurchaseSource::create([
                'id' => $source['id'],
                'name' => $source['name'],
                'status' => $source['status'],
            ]);
        }

        $this->command->info('Purchase Sources tablosu başarıyla dolduruldu: ' . count($purchaseSources) . ' kayıt eklendi');

        // Kısıtlamaları tekrar etkinleştir
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
