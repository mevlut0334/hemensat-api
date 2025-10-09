<?php
// 📁 database/migrations/xxxx_xx_xx_create_images_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->morphs('imageable'); // Bu, imageable_id ve imageable_type ekler

            // File Information
            $table->string('filename', 255);
            $table->string('original_name', 255);
            $table->string('path', 500); // Uzun path'ler için yeterli alan
            $table->string('mime_type', 100)->nullable();
            $table->unsignedInteger('size')->default(0); // Unsigned integer daha uygun

            // Image Metadata (performans için)
            $table->unsignedSmallInteger('width')->nullable();
            $table->unsignedSmallInteger('height')->nullable();
            $table->unsignedTinyInteger('order')->default(0); // Görsel sıralaması
            $table->boolean('is_primary')->default(false); // Ana görsel mi?

            // Status
            $table->enum('status', ['active', 'processing', 'failed', 'deleted'])
                ->default('processing');

            $table->timestamps();

            // Performance Indexes
            $table->index(['imageable_id', 'imageable_type', 'status', 'order']);
            $table->index(['imageable_id', 'imageable_type', 'is_primary']);
            $table->index(['status', 'created_at']); // Failed/processing cleanup için
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
