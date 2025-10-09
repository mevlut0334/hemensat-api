<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\ImageResource;
use App\Http\Resources\ImageCollection;
use App\Contracts\ImageServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    protected $imageService;

    public function __construct(ImageServiceInterface $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * İlanın tüm resimlerini listele
     */
    public function index(int $listingId): JsonResponse
    {
        $images = $this->imageService->getListingImages($listingId);

        return response()->json(new ImageCollection($images));
    }

    /**
     * İlanın aktif resimlerini listele
     */
    public function active(int $listingId): JsonResponse
    {
        $images = $this->imageService->getActiveImages($listingId);

        return response()->json(new ImageCollection($images));
    }

    /**
     * İlanın birincil resmini getir
     */
    public function primary(int $listingId): JsonResponse
    {
        $image = $this->imageService->getPrimaryImage($listingId);

        if (!$image) {
            return response()->json(["message" => "Birincil resim bulunamadı"], 404);
        }

        return response()->json(new ImageResource($image));
    }

    /**
     * İlanın ilk resmini getir
     */
    public function first(int $listingId): JsonResponse
    {
        $image = $this->imageService->getFirstImage($listingId);

        if (!$image) {
            return response()->json(["message" => "Resim bulunamadı"], 404);
        }

        return response()->json(new ImageResource($image));
    }

    /**
     * Yeni resim yükle
     */
    public function store(Request $request): JsonResponse
    {
        // Manuel doğrulama
        $validated = $request->validate([
            "images" => "required|array|max:10", // En fazla 10 resim
            "images.*" => "required|file|image|mimes:jpeg,png,jpg,gif,webp|max:10240", // 10MB max
            "sale_listing_id" => "required|integer|exists:sale_listings,id",
        ]);

        $listingId = $validated["sale_listing_id"];
        $images = $request->file("images");

        $imageData = [];

        if (is_array($images)) {
            foreach ($images as $index => $image) {
                if ($image->isValid()) {
                    // Resmi storage"a kaydet
                    $path = $image->store("listings/{$listingId}", "public");

                    $imageData[] = [
                        "sale_listing_id" => $listingId,
                        "path" => $path,
                        "filename" => $image->getClientOriginalName(),
                        "original_name" => $image->getClientOriginalName(),
                        "size" => $image->getSize(),
                        "mime_type" => $image->getMimeType(),
                        "width" => getimagesize($image)[0] ?? 0,
                        "height" => getimagesize($image)[1] ?? 0,
                        "status" => "active",
                        "is_primary" => $index === 0, // İlk resmi birincil yap
                        "order" => $index,
                    ];
                }
            }
        }

        if (empty($imageData)) {
            return response()->json(["message" => "Geçerli resim bulunamadı"], 400);
        }

        $createdImages = $this->imageService->createMultipleImages($imageData);

        return response()->json([
            "message" => "Resimler başarıyla yüklendi",
            "images" => ImageResource::collection($createdImages),
        ], 201);
    }

    /**
     * Resim bilgilerini güncelle
     */
    public function update(Request $request, int $id): JsonResponse
    {
        // Manuel doğrulama
        $validated = $request->validate([
            "is_primary" => "sometimes|required|boolean",
            "order" => "sometimes|required|integer|min:0",
            "status" => "sometimes|required|string|in:active,processing,failed,deleted",
        ]);

        $updated = $this->imageService->updateImage($id, $validated);

        if (!$updated) {
            return response()->json(["message" => "Resim güncellenemedi"], 400);
        }

        // Eğer birincil resim olarak işaretlendiyse, diğer resimleri güncelle
        if (!empty($validated["is_primary"]) && $validated["is_primary"]) {
            $image = $this->imageService->getImageById($id);
            if ($image) {
                $this->imageService->setPrimaryImage($id, $image["sale_listing_id"]);
            }
        }

        $image = $this->imageService->getImageById($id);

        return response()->json([
            "message" => "Resim başarıyla güncellendi",
            "image" => new ImageResource($image),
        ]);
    }

    /**
     * Resmi sil
     */
    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->imageService->deleteImage($id);

        if (!$deleted) {
            return response()->json(["message" => "Resim silinemedi"], 400);
        }

        return response()->json(["message" => "Resim başarıyla silindi"]);
    }

    /**
     * Resmi birincil yap
     */
    public function setPrimary(int $imageId, int $listingId): JsonResponse
    {
        $set = $this->imageService->setPrimaryImage($imageId, $listingId);

        if (!$set) {
            return response()->json(["message" => "Resim birincil yapılamadı"], 400);
        }

        return response()->json(["message" => "Resim başarıyla birincil yapıldı"]);
    }

    /**
     * Resimleri yeniden sırala
     */
    public function reorder(Request $request): JsonResponse
    {
        // Manuel doğrulama
        $validated = $request->validate([
            "sale_listing_id" => "required|integer|exists:sale_listings,id",
            "image_orders" => "required|array",
            "image_orders.*.image_id" => "required|integer|exists:images,id",
            "image_orders.*.order" => "required|integer|min:0",
        ]);

        $listingId = $validated["sale_listing_id"];

        // image_orders dizisini image_id => order formatına dönüştür
        $imageOrder = [];
        foreach ($validated["image_orders"] as $item) {
            $imageOrder[$item["order"]] = $item["image_id"];
        }

        $reordered = $this->imageService->reorderImages($listingId, $imageOrder);

        if (!$reordered) {
            return response()->json(["message" => "Resimler yeniden sıralanamadı"], 400);
        }

        return response()->json(["message" => "Resimler başarıyla yeniden sıralandı"]);
    }

    /**
     * İlanın başarısız resimlerini listele
     */
    public function failed(int $listingId): JsonResponse
    {
        $images = $this->imageService->getFailedImages($listingId);

        return response()->json(new ImageCollection($images));
    }

    /**
     * İlanın işlenmekte olan resimlerini listele
     */
    public function processing(int $listingId): JsonResponse
    {
        $images = $this->imageService->getProcessingImages($listingId);

        return response()->json(new ImageCollection($images));
    }

    /**
     * İlanın resim istatistiklerini getir
     */
    public function stats(int $listingId): JsonResponse
    {
        $stats = $this->imageService->getImageStats($listingId);

        return response()->json($stats);
    }

    /**
     * İlanın resimlerinin toplam boyutunu getir
     */
    public function totalSize(int $listingId): JsonResponse
    {
        $totalSize = $this->imageService->getTotalImagesSize($listingId);

        return response()->json([
            "total_size" => $totalSize,
            "formatted_size" => $this->formatFileSize($totalSize),
        ]);
    }

    /**
     * Format file size to human readable format
     *
     * @param int $bytes
     * @return string
     */
    private function formatFileSize($bytes)
    {
        if ($bytes < 1024) {
            return $bytes . " B";
        } elseif ($bytes < 1048576) {
            return round($bytes / 1024, 2) . " KB";
        } else {
            return round($bytes / 1048576, 2) . " MB";
        }
    }
}
