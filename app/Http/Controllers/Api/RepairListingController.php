<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRepairListingRequest;
use App\Http\Requests\UpdateRepairListingRequest;
use App\Contracts\RepairListingServiceInterface;
use App\Http\Resources\RepairListingResource;
use App\Http\Resources\RepairListingDetailResource;
use App\Models\RepairListing;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\RepairListingFilterRequest;

class RepairListingController extends Controller
{
    protected RepairListingServiceInterface $repairListingService;

    public function __construct(RepairListingServiceInterface $repairListingService)
    {
        $this->repairListingService = $repairListingService;
    }

    /**
     * Tüm yayınlanmış ilanları listeler (herkes görebilir - sadece temel bilgiler).
     */
   /**
 * Tüm yayınlanmış ilanları listeler (herkes görebilir - sadece temel bilgiler).
 */
public function index(RepairListingFilterRequest $request): JsonResponse
{
    // Filtreleri al
    $filters = $request->getFilters();

    // Pagination parametrelerini al
    $paginationParams = $request->getPaginationParams();

    // Sorting parametrelerini al
    $sortingParams = $request->getSortingParams();

    // Filtrelenmiş ilanları getir
    $data = $this->repairListingService->getFilteredListings(
        $filters,
        $sortingParams,
        $paginationParams['per_page']
    );

    return response()->json($data);
}

    /**
     * Sadece abone olan kullanıcılar için ilan detayları
     * Route: GET /api/v1/repair-listings/{id} (middleware: subscriber)
     */
    public function showForSubscriber(int $id): JsonResponse
    {
        $user = Auth::user(); // Middleware sayesinde kesinlikle var ve abone

        $listing = $this->repairListingService->getListingModelById($id);

        if (!$listing) {
            return response()->json(['message' => 'Tamir ilanı bulunamadı.'], 404);
        }

        $isOwner = $user->id === $listing->user_id;

        // Tekliflerle birlikte yükle
        $listingWithRelations = $this->repairListingService->getListingWithOffersForUser(
            $id,
            $user->id,
            $isOwner
        );

        return response()->json([
            'listing' => new RepairListingDetailResource($listingWithRelations),
            'viewer_type' => $isOwner ? 'owner' : 'subscriber'
        ]);
    }

    /**
     * Kendi ilanını detaylı görme (sahip için - abone olmasa da görebilir)
     * Route: GET /api/v1/my-repair-listings/{id} (middleware: auth)
     */
    public function showOwn(int $id): JsonResponse
    {
        $user = Auth::user();

        $listing = $this->repairListingService->getListingModelById($id);

        if (!$listing) {
            return response()->json(['message' => 'Tamir ilanı bulunamadı.'], 404);
        }

        // Sadece kendi ilanını görebilir
        if ($user->id !== $listing->user_id) {
            return response()->json(['message' => 'Bu ilanı görüntüleme yetkiniz yok.'], 403);
        }

        // Sahip olduğu için tüm teklifleri görebilir
        $listingWithRelations = $this->repairListingService->getListingWithOffersForUser(
            $id,
            $user->id,
            true // Owner
        );

        return response()->json([
            'listing' => new RepairListingDetailResource($listingWithRelations),
            'viewer_type' => 'owner'
        ]);
    }

    /**
     * Yeni bir tamir ilanı oluşturur.
     */
    public function store(StoreRepairListingRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        $userId = Auth::id();
        $validatedData['user_id'] = $userId;

        // Phone alanını da validated data'ya ekle - zaten validation'dan geçmiş
        // StoreRepairListingRequest'te phone kuralı olduğu için otomatik gelecek

        $tempImagePaths = [];

        if ($request->hasFile("images")) {
            $images = $request->file("images");
            if (!is_array($images)) {
                $images = [$images];
            }

            foreach ($images as $index => $image) {
                if ($image->isValid()) {
                    $tempPath = "temp/" . uniqid() . "_" . $image->getClientOriginalName();
                    Storage::disk("local")->put($tempPath, file_get_contents($image->getPathname()));
                    $tempImagePaths[] = $tempPath;
                } else {
                    // Geçersiz resim durumu için log
                }
            }
        }

        try {
            // Service'e tüm validated data'yı gönder (phone dahil)
            $listing = $this->repairListingService->createListing($validatedData);
            $listingId = $listing->id ?? null;

            if (!$listingId) {
                Log::error('❌ Repair Listing ID is missing after creation', ['listing' => $listing]);
                throw new \Exception('Repair Listing ID is missing after creation');
            }

            if (!empty($tempImagePaths)) {
                \App\Jobs\ProcessImageUpload::dispatch(
                    $listingId,
                    'repair',
                    $tempImagePaths,
                    true
                );
            }

            return response()->json([
                "message" => "Tamir ilanınız başarıyla oluşturuldu. Fotoğraflarınız arka planda yükleniyor.",
                "listing" => new RepairListingResource($listing),
                "images_processing" => count($tempImagePaths),
            ], 201);

        } catch (\Exception $e) {
            Log::error("💥 Repair İlan oluşturma hatası: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            foreach ($tempImagePaths as $tempPath) {
                if (Storage::disk('local')->exists($tempPath)) {
                    Storage::disk('local')->delete($tempPath);
                }
            }

            return response()->json([
                "message" => "Tamir ilanı oluşturulurken bir hata oluştu.",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Var olan bir ilanı günceller.
     */
    public function update(UpdateRepairListingRequest $request, RepairListing $repairListing): JsonResponse
    {
        $validatedData = $request->validated();

        // Phone alanı da validated data'da olacak (UpdateRepairListingRequest'te tanımlandığı için)

        if ($this->repairListingService->updateListing($repairListing->id, $validatedData)) {
            return response()->json([
                'message' => 'Tamir ilanı başarıyla güncellendi.',
                'listing' => new RepairListingResource($repairListing->fresh()),
            ]);
        }

        return response()->json(['message' => 'Güncelleme başarısız oldu veya veri değişmedi.'], 500);
    }

    /**
     * Belirli bir ilanı siler (Soft Delete).
     */
    public function destroy(RepairListing $repairListing): JsonResponse
    {
        if ($this->repairListingService->deleteListing($repairListing->id)) {
            return response()->json(['message' => 'Tamir ilanı başarıyla silindi.'], 200);
        }

        return response()->json(['message' => 'Silme işlemi başarısız oldu.'], 500);
    }

    // === DURUM YÖNETİMİ METOTLARI ===

    public function publish(RepairListing $repairListing): JsonResponse
    {
        if ($this->repairListingService->publishListing($repairListing->id)) {
            return response()->json(['message' => 'Tamir ilanı başarıyla yayınlandı.'], 200);
        }
        return response()->json(['message' => 'Yayınlama başarısız oldu.'], 400);
    }

    public function complete(RepairListing $repairListing): JsonResponse
    {
        if ($this->repairListingService->markAsCompleted($repairListing->id)) {
            return response()->json(['message' => 'Tamir ilanı tamamlandı olarak işaretlendi.'], 200);
        }
        return response()->json(['message' => 'İşaretleme başarısız oldu.'], 400);
    }

    public function manualDeleteCheck(RepairListing $repairListing): JsonResponse
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Kullanıcı doğrulanamadı'], 401);
        }

        if (!auth()->user()->can('delete', $repairListing)) {
            return response()->json([
                'error' => 'Bu tamir ilanını silme yetkiniz yok.',
                'user_id' => auth()->id(),
                'listing_user_id' => $repairListing->user_id,
                'listing_id' => $repairListing->id
            ], 403);
        }

        return response()->json([
            'success' => 'Bu tamir ilanını silme yetkiniz var!',
            'user_id' => auth()->id(),
            'listing_user_id' => $repairListing->user_id,
            'listing_id' => $repairListing->id
        ]);
    }

    public function userListings(Request $request): JsonResponse
    {
        $user = Auth::user();

        $data = $this->repairListingService->getUserListings(
            $user->id,
            $request->get('per_page', 20)
        );

        return response()->json([
            'message' => 'Kendi repair ilanlarınız başarıyla listelendi.',
            'data' => $data
        ]);
    }
}
