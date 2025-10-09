<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use App\Models\SaleListing;
use App\Models\RepairListing;

class CanMakeOffer
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        // Kimlik doğrulama kontrolü
        if (!$user) {
            return response()->json([
                "message" => "Kimlik doğrulama gereklidir.",
                "error" => "unauthenticated"
            ], 401);
        }

        // POST /api/offers rotasında ilan bilgileri Request body'den gelir
        $offerableType = $request->input('offerable_type');
        $offerableId = $request->input('offerable_id');

        // Abonelik kontrolü
        if (!$user->isSubscriber()) {
            return response()->json([
                "message" => "Teklif verebilmek için abone olmanız gerekmektedir.",
                "error" => "subscription_required"
            ], 403);
        }

        // İlan tipi ve ID kontrolü
        if (!$offerableType || !$offerableId) {
            return response()->json([
                "message" => "İlan tipi (offerable_type) ve ID (offerable_id) gereklidir.",
                "error" => "invalid_listing_data"
            ], 400);
        }

        // İlan tipini normalize et ve model class'ını belirle
        $modelClass = null;

        if ($offerableType === 'sale' || $offerableType === 'App\Models\SaleListing') {
            $modelClass = SaleListing::class;
        } elseif ($offerableType === 'repair' || $offerableType === 'App\Models\RepairListing') {
            $modelClass = RepairListing::class;
        } elseif (class_exists($offerableType)) {
            // Tam class adı verildiyse ve class mevcutsa
            $modelClass = $offerableType;
        } else {
            return response()->json([
                "message" => "Geçersiz ilan tipi. 'sale' veya 'repair' olmalıdır.",
                "error" => "invalid_offerable_type"
            ], 400);
        }

        // Eloquent ile ilanı bul
        $listing = $modelClass::find($offerableId);

        if (!$listing) {
            return response()->json([
                "message" => "Teklif verilmek istenen ilan bulunamadı.",
                "error" => "listing_not_found"
            ], 404);
        }

        // Kendi ilanına teklif veremez
        if ($user->id === $listing->user_id) {
            return response()->json([
                "message" => "Kendi ilanınıza teklif veremezsiniz.",
                "error" => "cannot_offer_own_listing"
            ], 403);
        }

        // Sadece aktif ilanlara teklif verilebilir
        if ($listing->status !== "active") {
            return response()->json([
                "message" => "Sadece aktif ilanlara teklif verebilirsiniz. İlan durumu: " . $listing->status,
                "error" => "listing_not_active"
            ], 403);
        }

        // İlan tipine özel ek kontroller
        if ($listing instanceof SaleListing) {
            // Sale listing için: satılmış ürüne teklif verilemez
            if ($listing->status === 'sold') {
                return response()->json([
                    "message" => "Bu ürün zaten satılmış.",
                    "error" => "product_already_sold"
                ], 403);
            }
        } elseif ($listing instanceof RepairListing) {
            // Repair listing için: tamamlanmış işe teklif verilemez
            if ($listing->status === 'completed') {
                return response()->json([
                    "message" => "Bu tamir işi zaten tamamlanmış.",
                    "error" => "repair_already_completed"
                ], 403);
            }
        }

        // Gate kontrolü (ek güvenlik katmanı)
        if (!Gate::allows('make-offer', $listing)) {
            return response()->json([
                "message" => "Bu ilana teklif verme yetkiniz bulunmamaktadır.",
                "error" => "offer_not_allowed"
            ], 403);
        }

        // Controller'a kolay erişim için ilanı ve normalize edilmiş tipi Request nesnesine ekle
        $request->merge([
            'listing' => $listing,
            'normalized_offerable_type' => $modelClass
        ]);

        return $next($request);
    }
}
