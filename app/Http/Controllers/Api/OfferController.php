<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OfferStoreRequest;
use App\Http\Resources\OfferResource;
use App\Contracts\OfferServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class OfferController extends Controller
{
    protected $offerService;

    public function __construct(OfferServiceInterface $offerService)
    {
        $this->offerService = $offerService;
    }

    /**
     * POST /api/offers
     * Yeni bir teklif oluşturur.
     */
    public function store(OfferStoreRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        try {
            $offer = $this->offerService->submitOffer($data);

            return response()->json([
                'message' => 'Teklifiniz başarıyla oluşturuldu ve beklemede.',
                'offer' => new OfferResource($offer),
            ], 201);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Teklif oluşturulurken bir hata oluştu: ' . $e->getMessage()], 400);
        }
    }

    /**
     * GET /api/listings/{type}/{id}/offers
     * İlan sahibinin kendi ilanına yapılan teklifleri listeler.
     */
    public function index(Request $request, string $type, int $id): JsonResponse
    {
        $listingType = 'App\\Models\\' . ucfirst($type) . 'Listing';

        $listingModel = $listingType::findOrFail($id);

        if (Gate::denies('manage-offer', $listingModel)) {
             return response()->json(['message' => 'Bu ilan için teklifleri görüntüleme yetkiniz yok.'], 403);
        }

        $offers = $this->offerService->getOffersForListing($id, $listingType);

        return response()->json([
            'listing_id' => $id,
            'listing_type' => $type,
            'offers' => OfferResource::collection($offers),
        ]);
    }

    /**
     * PATCH /api/offers/{offer}
     * Teklifin durumunu (kabul/red) günceller.
     */
    public function update(Request $request, int $offerId): JsonResponse
    {
        $action = $request->input('action');

        $offer = $this->offerService->findOfferById($offerId);

        if (!$offer) {
             return response()->json(['message' => 'Teklif bulunamadı.'], 404);
        }

        $listing = $offer->offerable;

        if (Gate::denies('manage-offer', $listing)) {
             return response()->json(['message' => 'Bu teklif üzerinde işlem yapma yetkiniz yok.'], 403);
        }

        $success = false;
        $message = 'Geçersiz işlem veya eksik parametre.';

        if ($action === 'accept') {
            $success = $this->offerService->acceptOffer($offerId);
            $message = $success ? 'Teklif başarıyla kabul edildi. İlgili tüm teklifler reddedildi.' : 'Teklif kabul edilemedi veya zaten kabul edilmiş/reddedilmiş.';
        } elseif ($action === 'reject') {
            $success = $this->offerService->rejectOffer($offerId);
            $message = $success ? 'Teklif başarıyla reddedildi.' : 'Teklif reddedilemedi veya zaten reddedilmiş.';
        }

        return response()->json(['message' => $message, 'success' => $success], $success ? 200 : 400);
    }

     /**
     * GET /api/offers/my-received
     * Kullanıcının tüm ilanlarına gelen teklifleri listeler.
     */
    public function myReceivedOffers(Request $request): JsonResponse
    {
        $userId = auth()->id();

        try {
            Log::info('🔍 MyReceivedOffers başladı', ['user_id' => $userId]);

            $offers = $this->offerService->getOffersForUser($userId);

            Log::info('✅ Teklifler getirildi', [
                'user_id' => $userId,
                'count' => $offers->count(),
                'offers' => $offers->toArray()
            ]);

            $resource = OfferResource::collection($offers);

            Log::info('✅ Resource oluşturuldu', [
                'resource' => $resource->toArray($request)
            ]);

            return response()->json([
                'message' => 'İlanlarınıza gelen teklifler başarıyla getirildi.',
                'offers' => $resource,
                'total' => $offers->count()
            ]);

        } catch (\Exception $e) {
            Log::error('❌ MyReceivedOffers Hatası', [
                'user_id' => $userId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'Teklifler getirilirken bir hata oluştu: ' . $e->getMessage(),
                'error_detail' => $e->getTraceAsString()
            ], 500);
        }
    }

    /**
     * GET /api/offers/my-sent
     * Kullanıcının verdiği teklifleri listeler.
     */
     public function mySentOffers(Request $request): JsonResponse
    {
        $userId = auth()->id();

        try {
            Log::info('🔍 MySentOffers başladı', ['user_id' => $userId]);

            $offers = $this->offerService->getSentOffersByUser($userId);

            Log::info('✅ Gönderilen teklifler getirildi', [
                'user_id' => $userId,
                'count' => $offers->count(),
                'offers' => $offers->toArray()
            ]);

            $resource = OfferResource::collection($offers);

            Log::info('✅ Resource oluşturuldu', [
                'resource' => $resource->toArray($request)
            ]);

            return response()->json([
                'message' => 'Verdiğiniz teklifler başarıyla getirildi.',
                'offers' => $resource,
                'total' => $offers->count()
            ]);

        } catch (\Exception $e) {
            Log::error('❌ MySentOffers Hatası', [
                'user_id' => $userId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'Teklifler getirilirken bir hata oluştu: ' . $e->getMessage(),
                'error_detail' => $e->getTraceAsString()
            ], 500);
        }
    }

    /**
     * DELETE /api/offers/{offer}
     * Kullanıcının kendi verdiği teklifi siler.
     */
    public function destroy(int $offerId): JsonResponse
    {
        $userId = auth()->id();

        try {
            $offer = $this->offerService->findOfferById($offerId);

            if (!$offer) {
                return response()->json(['message' => 'Teklif bulunamadı.'], 404);
            }

            if ($offer->user_id !== $userId) {
                return response()->json(['message' => 'Bu teklifi silme yetkiniz yok.'], 403);
            }

            $success = $this->offerService->deleteOffer($offerId);

            if ($success) {
                return response()->json(['message' => 'Teklifiniz başarıyla silindi.']);
            } else {
                return response()->json(['message' => 'Teklif silinemedi. Sadece bekleyen teklifler silinebilir.'], 400);
            }

        } catch (\Exception $e) {
            return response()->json(['message' => 'Teklif silinirken bir hata oluştu: ' . $e->getMessage()], 500);
        }
    }
}
