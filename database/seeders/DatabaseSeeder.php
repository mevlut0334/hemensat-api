<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\Location\LocationSeeder;
use Database\Seeders\Device\DeviceSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🚀 Veritabanı seeding başlıyor...');
        $this->command->line('');

        // Lokasyon verilerini yükle (İl-İlçe)
        $this->command->info('📍 Lokasyon verileri yükleniyor...');
        $this->call(LocationSeeder::class);
        $this->command->line('');

        // Cihaz verilerini yükle (Marka-Model-Kapasite-Kaynak)
        $this->command->info('📱 Cihaz verileri yükleniyor...');
        $this->call(DeviceSeeder::class);
        $this->command->line('');

        $this->command->info('👤 Admin kullanıcı oluşturuluyor...');
        $this->call(AdminUserSeeder::class);
        $this->command->line('');

        $this->command->info('🎉 Tüm veriler başarıyla yüklendi!');
        $this->command->info('✅ Veritabanı seeding tamamlandı.');
    }
}
