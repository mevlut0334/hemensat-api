<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProvinceController;
use App\Http\Controllers\Api\DistrictController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SaleListingController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ImageController;
use App\Http\Controllers\Api\RepairListingController;
use App\Http\Controllers\Api\OfferController;
use App\Http\Controllers\Api\FcmTokenController;
use App\Http\Controllers\Api\Admin\SubscriptionController;

// =============================================================================
// GENEL ERİŞİM - KİMLİK DOĞRULAMASI OLMAYAN ROTALAR
// =============================================================================

// Auth rotaları
Route::post("register", [AuthController::class, "register"]);
Route::post("login", [AuthController::class, "login"]);
Route::post("users", [UserController::class, "store"]);

// Lokasyon verileri
Route::prefix("location")->group(function () {
    Route::get("provinces", [ProvinceController::class, "index"]);
    Route::get("provinces/{provinceId}/districts", [DistrictController::class, "show"]);
});

// =============================================================================
// GİRİŞ YAPMIŞ KULLANICI ROTALARI (auth:sanctum)
// =============================================================================

Route::middleware("auth:sanctum")->group(function () {

    // === İLAN LİSTELERİ (Sadece giriş yapmış kullanıcılar) ===
    Route::get('repair-listings', [RepairListingController::class, 'index']);
    Route::get('sale-listings', [SaleListingController::class, 'index']);

    // User bilgisi ve çıkış
    Route::get("/user", function (Request $request) {
        return $request->user();
    });
    Route::post("logout", [AuthController::class, "logout"]);
    Route::apiResource("users", UserController::class)->except(["store"]);

    Route::get('/user/profile', function () {
        return response()->json([
            'data' => auth()->user()
        ]);
    });

    // --------------------------------------------------------------------------
    // SATIŞ İLANLARI (SALE LISTINGS)
    // --------------------------------------------------------------------------
    Route::prefix("sale-listings")->group(function () {

        // İlan oluşturma (tüm giriş yapmış kullanıcılar)
        Route::post("/", [SaleListingController::class, "store"]);

        // Kullanıcının kendi ilanları (abone olmaya gerek yok)
        Route::get("my", [SaleListingController::class, "userListings"]);
        Route::get("my/stats", [SaleListingController::class, "userStats"]);
        Route::get("my/{id}", [SaleListingController::class, "showOwn"])->where('id', '[0-9]+');

        // Dropdown verileri
        Route::get('brands', [\App\Http\Controllers\Api\BrandController::class, 'index']);
        Route::get('brands/{brandId}/models', [\App\Http\Controllers\Api\BrandController::class, 'models']);
        Route::get('storage-capacities', [\App\Http\Controllers\Api\StorageCapacityController::class, 'index']);
        Route::get('purchase-sources', [\App\Http\Controllers\Api\PurchaseSourceController::class, 'index']);

        // İlan yönetimi (sadece ilan sahibi)
        Route::put("/{saleListing}", [SaleListingController::class, "update"])
            ->where('saleListing', '[0-9]+')
            ->middleware('can:update,saleListing');

        Route::delete("/{saleListing}", [SaleListingController::class, "destroy"])
            ->where('saleListing', '[0-9]+')
            ->middleware('can:delete,saleListing');

        Route::post("/{saleListing}/publish", [SaleListingController::class, "publish"])
            ->where('saleListing', '[0-9]+')
            ->middleware('can:publish,saleListing');

        Route::post("/{saleListing}/deactivate", [SaleListingController::class, "deactivate"])
            ->where('saleListing', '[0-9]+')
            ->middleware('can:deactivate,saleListing');

        Route::post("/{saleListing}/mark-as-sold", [SaleListingController::class, "markAsSold"])
            ->where('saleListing', '[0-9]+')
            ->middleware('can:markAsSold,saleListing');

        // Başkasının ilan detayı - SADECE ABONELER (en sonda olmalı)
        Route::get("{id}", [SaleListingController::class, "showForSubscriber"])
            ->where('id', '[0-9]+')
            ->middleware('is.subscriber');
    });

    // --------------------------------------------------------------------------
    // TAMİR İLANLARI (REPAIR LISTINGS)
    // --------------------------------------------------------------------------
    Route::prefix("repair-listings")->group(function () {
        Route::post("/", [RepairListingController::class, "store"]);

        // Kullanıcının kendi ilanları (abone olmaya gerek yok)
        Route::get("my", [RepairListingController::class, "userListings"]);
        Route::get("my/{id}", [RepairListingController::class, "showOwn"])->where('id', '[0-9]+');

        Route::put("/{repairListing}", [RepairListingController::class, "update"])
            ->where('repairListing', '[0-9]+')
            ->middleware('can:update,repairListing');

        Route::delete("/{repairListing}", [RepairListingController::class, "destroy"])
            ->where('repairListing', '[0-9]+')
            ->middleware('can:delete,repairListing');

        Route::patch("/{repairListing}/publish", [RepairListingController::class, "publish"])
            ->where('repairListing', '[0-9]+')
            ->middleware('can:publish,repairListing');

        Route::patch("/{repairListing}/complete", [RepairListingController::class, "complete"])
            ->where('repairListing', '[0-9]+')
            ->middleware('can:complete,repairListing');

        // Dropdown verileri
        Route::get('brands', [\App\Http\Controllers\Api\BrandController::class, 'index']);
        Route::get('brands/{brandId}/models', [\App\Http\Controllers\Api\BrandController::class, 'models']);
        Route::get('storage-capacities', [\App\Http\Controllers\Api\StorageCapacityController::class, 'index']);
        Route::get('purchase-sources', [\App\Http\Controllers\Api\PurchaseSourceController::class, 'index']);

        // Başkasının ilan detayı - SADECE ABONELER (en sonda olmalı)
        Route::get("{id}", [RepairListingController::class, "showForSubscriber"])
            ->where('id', '[0-9]+')
            ->middleware('is.subscriber');
    });

    // --------------------------------------------------------------------------
    // RESİM İŞLEMLERİ
    // --------------------------------------------------------------------------
    Route::prefix("images")->group(function () {
        Route::prefix("listing")->group(function () {
            Route::get("/{listingId}", [ImageController::class, "index"]);
            Route::get("/{listingId}/active", [ImageController::class, "active"]);
            Route::get("/{listingId}/primary", [ImageController::class, "primary"]);
            Route::get("/{listingId}/first", [ImageController::class, "first"]);
            Route::get("/{listingId}/failed", [ImageController::class, "failed"]);
            Route::get("/{listingId}/processing", [ImageController::class, "processing"]);
            Route::get("/{listingId}/stats", [ImageController::class, "stats"]);
            Route::get("/{listingId}/total-size", [ImageController::class, "totalSize"]);
        });

        Route::post("/", [ImageController::class, "store"]);
        Route::put("/{id}", [ImageController::class, "update"]);
        Route::delete("/{id}", [ImageController::class, "destroy"]);
        Route::post("/{imageId}/listing/{listingId}/set-primary", [ImageController::class, "setPrimary"]);
        Route::post("/reorder", [ImageController::class, "reorder"]);
    });

    // --------------------------------------------------------------------------
    // TEKLİF İŞLEMLERİ
    // --------------------------------------------------------------------------
    Route::prefix('offers')->group(function () {

        // ✅ ABONE OLMADAN ERİŞİLEBİLİR (Sadece auth:sanctum gerekli)
        // Gelen teklifler (kendi ilanlarına gelen teklifler - abone olmaya gerek yok)
        Route::get('/my-received', [OfferController::class, 'myReceivedOffers']);

        // Gönderilen teklifler (geçmişte verdiği teklifler - abone olmaya gerek yok)
        Route::get('/my-sent', [OfferController::class, 'mySentOffers']);

        // Teklif güncelleme (kabul/red - ilan sahibi, abone olmaya gerek yok)
        Route::patch('/{offer}', [OfferController::class, 'update']);

        // Teklif silme (kendi teklifini silme - abone olmaya gerek yok)
        Route::delete('/{offer}', [OfferController::class, 'destroy']);

        // ❌ SADECE ABONELER (Yeni teklif verme için abonelik gerekli)
        Route::post('/', [OfferController::class, 'store'])
            ->middleware(['is.subscriber', 'can_make_offer']);
    });

    // İlana ait teklifleri görüntüleme (sadece ilan sahibi - abone olmaya gerek yok)
    Route::get('listings/{type}/{id}/offers', [OfferController::class, 'index']);

    // --------------------------------------------------------------------------
    // FCM TOKEN
    // --------------------------------------------------------------------------
    Route::post('/fcm-token', [FcmTokenController::class, 'store']);

    // HESAP SİLME
    // --------------------------------------------------------------------------
    Route::delete('/account', [\App\Http\Controllers\Api\AccountDeletionController::class, 'destroy']);

}); // ← auth:sanctum group'u burada bitiyor

// =============================================================================
// ADMİN ROTALARI (auth:sanctum + admin middleware)
// =============================================================================

Route::middleware(['auth:sanctum','is.admin'])->prefix('admin')->group(function () {

    // Abonelik yönetimi
    Route::prefix('subscriptions')->group(function () {
        Route::get('users', [SubscriptionController::class, 'index']);
        Route::post('users/{user}/toggle', [SubscriptionController::class, 'toggle']);
        Route::put('users/{user}', [SubscriptionController::class, 'update']);
    });

});
