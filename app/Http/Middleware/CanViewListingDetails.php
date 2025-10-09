<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\SaleListing;
use App\Models\RepairListing;

class CanViewListingDetails
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        // URL'den dinamik olarak ilan tipini ve ID'yi al
        $listingTypeAlias = $request->route("type"); // 'sale' veya 'repair'
        $listingId = $request->route("id");

        $modelClass = $this->resolveListingModel($listingTypeAlias);

        if (!$modelClass) {
            return response()->json(["message" => "Geçersiz ilan tipi.", "error" => "invalid_listing_type"], 400);
        }

        // İlanı bul
        $listing = $modelClass::find($listingId);

        if (!$listing) {
            return response()->json([
                "message" => "İlan bulunamadı.",
                "error" => "listing_not_found"
            ], 404);
        }

        // 1. İlan sahibi ise detayları görebilir
        if ($user && $user->id === $listing->user_id) {
            return $next($request);
        }

        // 2. Abone olanlar detayları görebilir
        // isSubscriber() metodunun User modelinizde tanımlı olduğunu varsayıyoruz.
        if ($user && $user->isSubscriber()) {
            return $next($request);
        }

        // Hiçbiri değilse erişim reddedilir
        return response()->json([
            "message" => "İlan detaylarını görüntülemek için abone olmanız gerekmektedir.",
            "error" => "subscription_required"
        ], 403);
    }

    // Polimorfik model çözümleyicisi
    private function resolveListingModel(string $alias): ?string
    {
        return match ($alias) {
            'sale' => SaleListing::class,
            'repair' => RepairListing::class,
            default => null,
        };
    }
}
