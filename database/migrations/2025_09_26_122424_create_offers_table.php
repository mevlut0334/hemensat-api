<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->id();

            // Teklifi Veren Kullanıcı
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Fiyat ve Durum
            $table->decimal('offer_price', 10, 2);
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');

             // Polimorfik İlişki: Hangi ilana ait olduğunu belirtir
            $table->string('offerable_type'); // 'App\Models\RepairListing' veya 'App\Models\SaleListing'
            $table->unsignedBigInteger('offerable_id'); // İlanın ID'si

             // Kabul/Red Detayları
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('rejected_at')->nullable();


            $table->timestamps();

            // Performans İndeksleri
            // Teklifleri ilana göre ve duruma göre hızla filtrelemek için
            $table->index(['offerable_type', 'offerable_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
