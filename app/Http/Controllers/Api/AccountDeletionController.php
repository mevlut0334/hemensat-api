<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\Offer;
use App\Models\RepairListing;
use App\Models\SaleListing;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AccountDeletionController extends Controller
{
    /**
     * Giriş yapmış kullanıcının kendi hesabını ve ona bağlı
     * tüm verileri (ilanlar, teklifler, resimler, fcm token, sanctum token)
     * kalıcı olarak siler.
     */
    public function destroy(Request $request): JsonResponse
    {
        $authUser = $request->user();

        if (!$authUser) {
            return response()->json([
                'message' => 'Kullanıcı bulunamadı.'
            ], 404);
        }
        // AuthUser yerine User modelini kullan (ilişkiler orada tanımlı)
    $user = \App\Models\User::find($authUser->id);

    if (!$user) {
        return response()->json([
            'message' => 'Kullanıcı bulunamadı.'
        ], 404);
    }

        try {
            DB::transaction(function () use ($user) {

                // 1) Kullanıcının ilan ID'lerini topla (soft-delete edilmiş olanlar dahil)
                $saleListingIds = $user->saleListings()->withTrashed()->pluck('id');
                $repairListingIds = $user->repairListings()->withTrashed()->pluck('id');

                // 2) Bu ilanlara gelen TÜM teklifleri sil (başkalarından gelenler dahil)
                if ($saleListingIds->isNotEmpty()) {
                    Offer::where('offerable_type', SaleListing::class)
                        ->whereIn('offerable_id', $saleListingIds)
                        ->get()
                        ->each(function (Offer $offer) {
                            $offer->delete();
                        });
                }

                if ($repairListingIds->isNotEmpty()) {
                    Offer::where('offerable_type', RepairListing::class)
                        ->whereIn('offerable_id', $repairListingIds)
                        ->get()
                        ->each(function (Offer $offer) {
                            $offer->delete();
                        });
                }

                // 3) Kullanıcının kendi verdiği teklifleri sil (başkasının ilanına verdiği teklifler)
                $user->offers()->get()->each(function (Offer $offer) {
                    $offer->delete();
                });

                // 4) İlanlara ait resimleri sil (Image::deleted event'i dosyayı storage'dan da siler)
                if ($saleListingIds->isNotEmpty()) {
                    Image::where('imageable_type', SaleListing::class)
                        ->whereIn('imageable_id', $saleListingIds)
                        ->get()
                        ->each(function (Image $image) {
                            $image->delete();
                        });
                }

                if ($repairListingIds->isNotEmpty()) {
                    Image::where('imageable_type', RepairListing::class)
                        ->whereIn('imageable_id', $repairListingIds)
                        ->get()
                        ->each(function (Image $image) {
                            $image->delete();
                        });
                }

                // 5) İlanları kalıcı olarak sil (soft delete kullanıyorlar, forceDelete ile tamamen kaldırıyoruz)
                $user->saleListings()->withTrashed()->forceDelete();
                $user->repairListings()->withTrashed()->forceDelete();

                // 6) FCM token'larını sil
                $user->fcmTokens()->delete();

                // 7) Sanctum API token'larını sil
                $user->tokens()->delete();

                // 8) Kullanıcıyı sil
                $user->delete();
            });
        } catch (\Throwable $e) {
            Log::error('Hesap silme işlemi başarısız oldu.', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Hesabınız silinirken bir hata oluştu. Lütfen daha sonra tekrar deneyin.'
            ], 500);
        }

        return response()->json([
            'message' => 'Hesabınız ve ilişkili tüm verileriniz başarıyla silindi.'
        ], 200);
    }
}
