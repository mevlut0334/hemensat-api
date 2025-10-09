<?php

namespace Database\Seeders\Device;

use Illuminate\Database\Seeder;
use Database\Seeders\Device\BrandSeeder;
use Database\Seeders\Device\ModelSeeder;
use Database\Seeders\Device\StorageCapacitySeeder;
use Database\Seeders\Device\PurchaseSourceSeeder;

class DeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('📱 Device seeding başlıyor...');
        $this->command->line('');

        // Sıralı olarak seeder'ları çalıştır
        $this->command->info('🏷️  Markalar yükleniyor...');
        $this->call(BrandSeeder::class);
        $this->command->line('');

        $this->command->info('📱 Modeller yükleniyor...');
        $this->call(ModelSeeder::class);
        $this->command->line('');

        $this->command->info('💾 Depolama kapasiteleri yükleniyor...');
        $this->call(StorageCapacitySeeder::class);
        $this->command->line('');

        $this->command->info('🌍 Satın alım kaynakları yükleniyor...');
        $this->call(PurchaseSourceSeeder::class);
        $this->command->line('');

        $this->command->info('✅ Device seeding tamamlandı!');
        $this->command->info('🎉 Tüm cihaz verileri başarıyla yüklendi.');
    }
}
