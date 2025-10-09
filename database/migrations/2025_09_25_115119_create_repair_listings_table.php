<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create("repair_listings", function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");

            // Basic Info (SaleListing ile Ortak Alanlar)
            $table->string("title", 200);
            $table->text("description"); // Tamir tanımı

            // Device Info (SaleListing ile Ortak Alanlar, ama esneklik sağlandı)
            $table->unsignedBigInteger("brand_id");
            $table->unsignedBigInteger("model_id");

            // Satışta zorunlu olan bu alanlar tamirde zorunlu değil.
            $table->unsignedBigInteger("storage_capacity_id")->nullable();
            $table->unsignedBigInteger("purchase_source_id")->nullable();

            // Location
            $table->unsignedBigInteger("province_id");
            $table->unsignedBigInteger("district_id");

            // Repair Specific Field (Tamir İlanına Özel Alanlar)
            $table->boolean("is_urgent")->default(false); // Opsiyonel aciliyet durumu
            $table->enum("preferred_repair_type", ["on_site", "shop", "mail_in"])->nullable();

            // Status & Timestamps
            $table->enum("status", ["draft", "active", "completed", "inactive", "deleted"])->default("draft");
            $table->timestamp("published_at")->nullable();
            $table->timestamp("expires_at")->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(["status", "published_at"]);
            $table->index(["brand_id", "model_id", "status"]);
            $table->index(["province_id", "district_id", "status"]);
            $table->index(["user_id", "status", "created_at"]);
        });

        // Foreign keys (Ayrı bir Schema::table bloğu, SaleListings migration'ınızdaki gibi)
        Schema::table("repair_listings", function (Blueprint $table) {
            $table->foreign("user_id")->references("id")->on("users")->onDelete("cascade");
            $table->foreign("brand_id")->references("id")->on("brands")->onDelete("restrict");
            $table->foreign("model_id")->references("id")->on("models")->onDelete("restrict");
            $table->foreign("storage_capacity_id")->references("id")->on("storage_capacities")->onDelete("restrict");
            $table->foreign("purchase_source_id")->references("id")->on("purchase_sources")->onDelete("restrict");
            $table->foreign("province_id")->references("id")->on("provinces")->onDelete("restrict");
            $table->foreign("district_id")->references("id")->on("districts")->onDelete("restrict");
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("repair_listings");
    }
};
