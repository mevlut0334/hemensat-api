<?php

namespace Database\Seeders\Traits;

use Illuminate\Support\Facades\File;

trait DeviceSeederTrait
{
    /**
     * JSON dosyasını okur ve array olarak döndürür
     *
     * @param string $fileName
     * @return array|null
     */
    protected function loadJsonData(string $fileName): ?array
    {
        $jsonPath = database_path("seeders/Data/Device/{$fileName}");

        // Dosya var mı kontrol et
        if (!File::exists($jsonPath)) {
            $this->command->error("{$fileName} dosyası bulunamadı: {$jsonPath}");
            return null;
        }

        // JSON dosyasını oku
        $jsonContent = File::get($jsonPath);
        $data = json_decode($jsonContent, true);

        // JSON geçerli mi kontrol et
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->command->error("{$fileName} dosyası geçersiz JSON formatında: " . json_last_error_msg());
            return null;
        }

        return $data;
    }

    /**
     * Seeder başarı mesajı gösterir
     *
     * @param string $tableName
     * @param int $count
     * @return void
     */
    protected function showSuccessMessage(string $tableName, int $count): void
    {
        $this->command->info("{$tableName} tablosu başarıyla dolduruldu: {$count} kayıt eklendi");
    }

    /**
     * Tablo verilerini temizler
     *
     * @param string $model
     * @return void
     */
    protected function truncateTable($model): void
    {
        $model::truncate();
        $this->command->warn($model . " tablosu temizlendi");
    }
}
