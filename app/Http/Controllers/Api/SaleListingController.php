<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\SaleListingResource;
use App\Http\Resources\SaleListingDetailResource;
use App\Contracts\SaleListingServiceInterface;
use App\Jobs\ProcessImageUpload;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreSaleListingRequest;
use App\Http\Requests\UpdateSaleListingRequest;
use App\Models\SaleListing;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Requests\SaleListingFilterRequest;

class SaleListingController extends Controller
{
    use AuthorizesRequests;
    protected $saleListingService;

    public function __construct(SaleListingServiceInterface $saleListingService)
    {
        $this->saleListingService = $saleListingService;
    }

    /**
     * Genel ilan listesi (herkes erişebilir)
     */
  /**
 * Genel ilan listesi (herkes erişebilir)
 */
public function index(SaleListingFilterRequest $request): JsonResponse
{
    // Filtreleri al
    $filters = $request->getFilters();

    // Pagination parametrelerini al
    $paginationParams = $request->getPaginationParams();

    // Sorting parametrelerini al
    $sortingParams = $request->getSortingParams();

    // Filtrelenmiş ilanları getir
    $data = $this->saleListingService->getFilteredListings(
        $filters,
        $sortingParams,
        $paginationParams['per_page']
    );

    return response()->json($data);
}

    /**
     * Yeni ilan oluştur (giriş yapmış kullanıcılar)
     */
    public function store(StoreSaleListingRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $validated['user_id'] = auth()->id();

        // Phone alanı zaten validated data'da (StoreSaleListingRequest'ten geliyor)

        $tempImagePaths = [];

        if ($request->hasFile("images")) {
            $images = $request->file("images");
            if (!is_array($images)) {
                $images = [$images];
            }

            foreach ($images as $image) {
                if ($image->isValid()) {
                    $tempPath = "temp/" . uniqid() . "_" . $image->getClientOriginalName();
                    Storage::disk("local")->put($tempPath, file_get_contents($image->getPathname()));
                    $tempImagePaths[] = $tempPath;
                }
            }
        }

        try {
            // Service'e tüm validated data gönder (phone dahil)
            $listing = $this->saleListingService->createListing($validated);
            $listingId = $listing['id'] ?? $listing->id;

            if (!empty($tempImagePaths)) {
                ProcessImageUpload::dispatch($listingId, 'sale', $tempImagePaths, true);
            }

            return response()->json([
                "message" => "İlanınız başarıyla oluşturuldu.",
                "listing" => new SaleListingResource($listing),
                "images_processing" => count($tempImagePaths),
            ], 201);

        } catch (\Exception $e) {
            Log::error("Sale listing creation error: " . $e->getMessage());

            // Temp dosyaları temizle
            foreach ($tempImagePaths as $tempPath) {
                if (Storage::disk('local')->exists($tempPath)) {
                    Storage::disk('local')->delete($tempPath);
                }
            }

            return response()->json([
                "message" => "İlan oluşturulurken bir hata oluştu.",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Kullanıcının kendi ilanlarını listele
     */
    public function userListings(Request $request): JsonResponse
    {
        $userId = auth()->id();
        $perPage = $request->get('per_page', 20);

        $data = $this->saleListingService->getUserListings($userId, $perPage);

        return response()->json($data);
    }

    /**
     * Kullanıcının kendi ilanının detayını görüntüle (abone olmasına gerek yok)
     */
    /**
 * Kullanıcının kendi ilanının detayını görüntüle (abone olmasına gerek yok)
 */
public function showOwn(int $id): JsonResponse
{
    $listingData = $this->saleListingService->getListingForDetail($id);

    if (!$listingData) {
        return response()->json(['message' => 'İlan bulunamadı.'], 404);
    }

    // user_id kullan (user.id yerine)
    if ($listingData['user_id'] !== auth()->id()) {
        return response()->json(['message' => 'Bu ilanı görüntüleme yetkiniz yok.'], 403);
    }

    return response()->json($listingData);
}

/**
 * Abone kullanıcılar için başkasının ilanının detayını görüntüle
 */
public function showForSubscriber(int $id): JsonResponse
{
    $listingData = $this->saleListingService->getListingForDetail($id);

    if (!$listingData) {
        return response()->json(['message' => 'İlan bulunamadı.'], 404);
    }

    // user_id'yi al
    $ownerId = $listingData['user_id'] ?? null;

    if (!$ownerId) {
        return response()->json(['message' => 'İlan sahibi bilgisi bulunamadı.'], 500);
    }

    // Eğer kendi ilanıysa
    if ($ownerId === auth()->id()) {
        return response()->json([
            'message' => 'Kendi ilanınızı görüntülemek için /sale-listings/my/{id} endpoint\'ini kullanın.'
        ], 400);
    }

    return response()->json($listingData);
}

    /**
     * İlanı güncelle (sadece ilan sahibi) - Şimdi UpdateSaleListingRequest kullanıyor
     */
    public function update(UpdateSaleListingRequest $request, SaleListing $saleListing): JsonResponse
    {
        $validated = $request->validated();

        // Phone alanı da validated data'da olacak (UpdateSaleListingRequest'te tanımlandığı için)

        if ($this->saleListingService->updateListing($saleListing->id, $validated)) {
            $listing = $this->saleListingService->getListingByIdOrFail($saleListing->id);

            return response()->json([
                'message' => 'İlan başarıyla güncellendi.',
                'listing' => new SaleListingResource($listing),
            ]);
        }

        return response()->json(['message' => 'Güncelleme başarısız oldu.'], 500);
    }

    /**
     * İlanı sil (sadece ilan sahibi)
     */
    public function destroy(SaleListing $saleListing): JsonResponse
    {
        if ($this->saleListingService->deleteListing($saleListing->id)) {
            return response()->json(['message' => 'İlan başarıyla silindi.'], 200);
        }

        return response()->json(['message' => 'Silme işlemi başarısız oldu.'], 404);
    }

    /**
     * İlanı yayınla (sadece ilan sahibi)
     */
    public function publish(SaleListing $saleListing): JsonResponse
    {
        if ($this->saleListingService->publishListing($saleListing->id)) {
            return response()->json(['message' => 'İlan başarıyla yayınlandı.'], 200);
        }
        return response()->json(['message' => 'Yayınlama başarısız oldu.'], 400);
    }

    /**
     * İlanı deaktif et (sadece ilan sahibi)
     */
    public function deactivate(SaleListing $saleListing): JsonResponse
    {
        if ($this->saleListingService->deactivateListing($saleListing->id)) {
            return response()->json(['message' => 'İlan başarıyla deaktif edildi.'], 200);
        }
        return response()->json(['message' => 'Deaktif etme işlemi başarısız oldu.'], 400);
    }

    /**
     * İlanı satıldı olarak işaretle (sadece ilan sahibi)
     */
    public function markAsSold(SaleListing $saleListing): JsonResponse
    {
        if ($this->saleListingService->markAsSold($saleListing->id)) {
            return response()->json(['message' => 'İlan satıldı olarak işaretlendi.'], 200);
        }
        return response()->json(['message' => 'İşaretleme başarısız oldu.'], 400);
    }

    /**
     * Kullanıcının ilan istatistikleri
     */
    public function userStats(): JsonResponse
    {
        $userId = auth()->id();
        $stats = $this->saleListingService->getUserListingStats($userId);

        return response()->json([
            'message' => 'İstatistikler başarıyla alındı.',
            'stats' => $stats
        ]);
    }
}
