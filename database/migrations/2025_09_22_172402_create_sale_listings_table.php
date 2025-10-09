<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create("sale_listings", function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");

            // Basic Info
            $table->string("title", 200);
            $table->text("description");


            // Device Info
            $table->unsignedBigInteger("brand_id");
            $table->unsignedBigInteger("model_id");
            $table->unsignedBigInteger("storage_capacity_id");
            $table->unsignedBigInteger("purchase_source_id")->nullable();

            // Location
            $table->unsignedBigInteger("province_id");
            $table->unsignedBigInteger("district_id");

            // Status & Stats
            $table->enum("status", ["draft", "active", "sold", "inactive", "deleted"])->default("draft");

            // Offer Statistics
            $table->unsignedInteger("pending_offers_count")->default(0);
            $table->unsignedInteger("accepted_offers_count")->default(0);
            $table->unsignedInteger("rejected_offers_count")->default(0);
            $table->unsignedInteger("total_offers_count")->default(0);

            // Price Information
            $table->decimal("highest_offer_price", 10, 2)->nullable();
            $table->decimal("latest_offer_price", 10, 2)->nullable();
            $table->decimal("average_offer_price", 10, 2)->nullable();

            // Timestamps
            $table->timestamp("last_offer_at")->nullable();
            $table->timestamp("published_at")->nullable();
            $table->timestamp("expires_at")->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(["status", "published_at"]);
            $table->index(["brand_id", "model_id", "status"]);
            $table->index(["province_id", "district_id", "status"]);
            $table->index(["user_id", "status", "created_at"]);
            $table->index(["storage_capacity_id", "status"]);
            $table->index(["status", "highest_offer_price"]);
            $table->index(["status", "total_offers_count"]);
            $table->index(["last_offer_at", "status"]);

        });

        // Foreign keys manuel olarak ekle
        Schema::table("sale_listings", function (Blueprint $table) {
            $table->foreign("user_id")->references("id")->on("users")->onDelete("cascade");
            $table->foreign("brand_id")->references("id")->on("brands")->onDelete("restrict");
            $table->foreign("model_id")->references("id")->on("models")->onDelete("restrict"); // Doğru tablo adı
            $table->foreign("storage_capacity_id")->references("id")->on("storage_capacities")->onDelete("restrict");
            $table->foreign("purchase_source_id")->references("id")->on("purchase_sources")->onDelete("restrict")->nullable();
            $table->foreign("province_id")->references("id")->on("provinces")->onDelete("restrict");
            $table->foreign("district_id")->references("id")->on("districts")->onDelete("restrict");
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("sale_listings");
    }
};
