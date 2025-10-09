<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\Image;
use App\Models\SaleListing;
use App\Models\RepairListing;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ProcessImageUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 300;
    public $tries = 3;
    public $backoff = [10, 30, 60];

    protected $listingId;
    protected $listingType;
    protected $imagePaths;
    protected $shouldSetPrimary;

    /**
     * @param int $listingId
     * @param string $listingType 'sale' veya 'repair'
     * @param array $imagePaths
     * @param bool $shouldSetPrimary
     */
    public function __construct($listingId, $listingType, $imagePaths, bool $shouldSetPrimary = false)
    {
        $this->listingId = $listingId;
        $this->listingType = strtolower($listingType);
        $this->imagePaths = is_array($imagePaths) ? $imagePaths : [$imagePaths];
        $this->shouldSetPrimary = $shouldSetPrimary;
    }

    public function handle()
    {
        try {
            try {
                $listing = $this->findListingModel();
            } catch (\Exception $e) {
                Log::error('Failed to find listing model', [
                    'id' => $this->listingId,
                    'type' => $this->listingType,
                    'error' => $e->getMessage()
                ]);
                $this->fail($e);
                return;
            }

            $processedImages = [];
            $successCount = 0;
            $failCount = 0;

            foreach ($this->imagePaths as $index => $imagePath) {
                $result = $this->processImage($listing, $imagePath, $index);

                if ($result['success']) {
                    $processedImages[] = $result['image'];
                    $successCount++;
                } else {
                    $failCount++;
                }
            }

            if ($this->shouldSetPrimary && !empty($processedImages) && !$this->hasPrimaryImage($listing)) {
                $this->setPrimaryImage($processedImages[0]);
            }

            $this->updateListingStatus($listing, $successCount);

        } catch (\Exception $e) {
            Log::error('Job failed with exception', [
                'listing_id' => $this->listingId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $this->fail($e);
        }
    }

    protected function findListingModel(): ?Model
    {
        switch ($this->listingType) {
            case 'sale':
                $model = SaleListing::find($this->listingId);

                if (!$model) {
                    Log::error('Sale listing not found', ['id' => $this->listingId]);
                    throw new \Exception("Sale listing with ID {$this->listingId} not found");
                }
                return $model;
            case 'repair':
                $model = RepairListing::find($this->listingId);
                if (!$model) {
                    Log::error('Repair listing not found', ['id' => $this->listingId]);
                    throw new \Exception("Repair listing with ID {$this->listingId} not found");
                }
                return $model;
            default:
                Log::error('Invalid listing type', ['type' => $this->listingType]);
                throw new \Exception("Invalid listing type: {$this->listingType}");
        }
    }

    private function processImage(Model $listing, string $imagePath, int $index): array
    {
        try {
            if (!$listing) {
                Log::error('Listing is null', [
                    'listing_id' => $this->listingId,
                    'listing_type' => $this->listingType
                ]);
                return ['success' => false, 'error' => 'Listing not found'];
            }

            if (!Storage::disk('local')->exists($imagePath)) {
                Log::error('Temp image not found', ['path' => $imagePath]);
                return ['success' => false, 'error' => 'File not found'];
            }

            $tempFileContent = Storage::disk('local')->get($imagePath);
            $tempFileSize = Storage::disk('local')->size($imagePath);
            $filename = basename($imagePath);
            $originalName = $this->extractOriginalName($filename);

            $validation = $this->validateImageFile($tempFileContent, $tempFileSize);
            if (!$validation['valid']) {
                Log::error("Image validation failed: {$validation['error']}", ['path' => $imagePath]);
                Storage::disk('local')->delete($imagePath);
                return ['success' => false, 'error' => $validation['error']];
            }

            // Dizin oluşturma
            $typeSegment = $this->listingType === 'sale' ? 'sale-listings' : 'repair-listings';
            $directory = "{$typeSegment}/{$listing->id}";

            if (!Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->makeDirectory($directory);
            }

            // Görseli optimize et
            $optimizedImage = $this->optimizeImage($tempFileContent, $validation['mime_type']);

            // Kalıcı dosya yolu oluşturma (her zaman .jpg olarak kaydet)
            $uniqueFilename = uniqid() . '_' . time() . '.jpg';
            $permanentPath = "{$directory}/{$uniqueFilename}";

            // Optimize edilmiş dosyayı kaydet
            Storage::disk('public')->put($permanentPath, $optimizedImage['content']);

            // Veritabanına kaydetme verileri hazırla
            $imageableType = get_class($listing);

            $imageData = [
                'imageable_type' => $imageableType,
                'imageable_id' => $listing->id,
                'path' => $permanentPath,
                'filename' => $uniqueFilename,
                'original_name' => $originalName,
                'size' => strlen($optimizedImage['content']),
                'mime_type' => 'image/jpeg',
                'width' => $optimizedImage['width'],
                'height' => $optimizedImage['height'],
                'order' => $index,
                'status' => 'active',
                'is_primary' => false
            ];

            $image = $this->saveImageToDatabase($imageData);

            // Geçici dosyayı sil
            Storage::disk('local')->delete($imagePath);

            // Thumbnail oluştur
            $this->createThumbnail($permanentPath);

            Log::info("Image processed successfully", [
                'listing_id' => $listing->id,
                'index' => $index,
                'original_size' => $tempFileSize,
                'optimized_size' => strlen($optimizedImage['content']),
                'compression_ratio' => round((1 - strlen($optimizedImage['content']) / $tempFileSize) * 100, 2) . '%'
            ]);

            return ['success' => true, 'image' => $image];

        } catch (\Exception $e) {
            Log::error("Error processing image {$index}: " . $e->getMessage(), [
                'path' => $imagePath,
                'trace' => $e->getTraceAsString()
            ]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Görseli optimize et
     */
    private function optimizeImage(string $imageContent, string $mimeType): array
    {
        try {
            $manager = new ImageManager(new Driver());
            $image = $manager->read($imageContent);

            // Görseli otomatik yönlendir (EXIF verilerine göre)
            $image->orient();

            // Genişliği 1920px'e küçült (oran korunur)
            if ($image->width() > 1920) {
                $image->scale(width: 1920);
            }

            // JPEG formatında %85 kalite ile encode et
            $encoded = $image->toJpeg(quality: 85);

            return [
                'content' => (string) $encoded,
                'width' => $image->width(),
                'height' => $image->height()
            ];

        } catch (\Exception $e) {
            Log::warning('Image optimization failed, using original', [
                'error' => $e->getMessage()
            ]);

            // Optimizasyon başarısız olursa orijinali kullan
            try {
                $imgInfo = getimagesizefromstring($imageContent);
                return [
                    'content' => $imageContent,
                    'width' => $imgInfo[0] ?? null,
                    'height' => $imgInfo[1] ?? null
                ];
            } catch (\Exception $e2) {
                return [
                    'content' => $imageContent,
                    'width' => null,
                    'height' => null
                ];
            }
        }
    }

    private function saveImageToDatabase(array $imageData): Image
    {
        return DB::transaction(function () use ($imageData) {
            $requiredFields = ['imageable_type', 'imageable_id', 'path', 'filename', 'original_name'];
            foreach ($requiredFields as $field) {
                if (!isset($imageData[$field]) || empty($imageData[$field])) {
                    Log::error("Required field '{$field}' is missing or empty", [
                        'image_data' => $imageData
                    ]);
                    throw new \Exception("Required field '{$field}' is missing or empty");
                }
            }

            try {
                $image = Image::create($imageData);
                return $image;
            } catch (\Exception $e) {
                Log::error('Image::create() failed with error:', [
                    'error_message' => $e->getMessage(),
                    'error_code' => $e->getCode(),
                    'sql_error' => $e->getPrevious() ? $e->getPrevious()->getMessage() : 'No SQL error',
                    'image_data' => $imageData
                ]);
                throw $e;
            }
        });
    }

    private function hasPrimaryImage(Model $listing): bool
    {
        return Image::where('imageable_type', get_class($listing))
            ->where('imageable_id', $listing->id)
            ->where('is_primary', true)
            ->exists();
    }

    private function setPrimaryImage(Image $image): void
    {
        try {
            DB::transaction(function () use ($image) {
                // Önce diğer resimlerin primary'sini kaldır
                Image::where('imageable_type', $image->imageable_type)
                    ->where('imageable_id', $image->imageable_id)
                    ->update(['is_primary' => false]);

                // Bu resmi primary yap
                $image->update(['is_primary' => true]);
            });
        } catch (\Exception $e) {
            Log::error('Failed to set primary image', [
                'image_id' => $image->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    private function updateListingStatus(Model $listing, int $successCount): void
    {
        try {
            if ($successCount > 0 && $listing->status === 'draft') {
                $listing->update([
                    'status' => 'active',
                    'published_at' => now()
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to update listing status', [
                'listing_id' => $listing->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    private function validateImageFile(string $fileContent, int $fileSize): array
    {
        if ($fileSize > 10 * 1024 * 1024) {
            return ['valid' => false, 'error' => 'File too large'];
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_buffer($finfo, $fileContent);
        finfo_close($finfo);

        $allowedMimes = [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp',
            'image/bmp'
        ];

        if (!in_array($mimeType, $allowedMimes)) {
            return ['valid' => false, 'error' => 'Invalid file type'];
        }

        return ['valid' => true, 'mime_type' => $mimeType];
    }

    private function getImageDimensions(string $path): array
    {
        try {
            $fullPath = storage_path('app/public/' . $path);

            if (!file_exists($fullPath)) {
                return ['width' => null, 'height' => null];
            }

            $imageInfo = getimagesize($fullPath);

            return [
                'width' => $imageInfo[0] ?? null,
                'height' => $imageInfo[1] ?? null
            ];
        } catch (\Exception $e) {
            Log::warning('Could not get image dimensions', [
                'path' => $path,
                'error' => $e->getMessage()
            ]);

            return ['width' => null, 'height' => null];
        }
    }

    private function extractOriginalName(string $filename): string
    {
        if (preg_match('/^[a-f0-9]+_(.+)$/i', $filename, $matches)) {
            return $matches[1];
        }

        return $filename;
    }

    private function createThumbnail(string $imagePath): void
    {
        try {
            $fullPath = storage_path('app/public/' . $imagePath);

            if (!file_exists($fullPath)) {
                Log::warning('Source image not found for thumbnail', ['path' => $imagePath]);
                return;
            }

            // Thumbnail path
            $pathInfo = pathinfo($imagePath);
            $thumbnailPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '_thumb.' . $pathInfo['extension'];

            $this->generateThumbnail($imagePath, $thumbnailPath);
        } catch (\Exception $e) {
            Log::warning('Could not create thumbnail', [
                'path' => $imagePath,
                'error' => $e->getMessage()
            ]);
        }
    }

    private function generateThumbnail(string $sourcePath, string $thumbnailPath): void
    {
        try {
            $fullSourcePath = storage_path('app/public/' . $sourcePath);

            $manager = new ImageManager(new Driver());
            $thumbnail = $manager->read($fullSourcePath);

            // 300x300 boyutunda thumbnail (oran korunur)
            $thumbnail->scale(width: 300);

            // JPEG formatında %80 kalite ile encode et
            $encoded = $thumbnail->toJpeg(quality: 80);

            // Storage'a kaydet
            Storage::disk('public')->put($thumbnailPath, (string) $encoded);

            Log::info('Thumbnail created successfully', [
                'source' => $sourcePath,
                'thumbnail' => $thumbnailPath
            ]);

        } catch (\Exception $e) {
            Log::warning('Thumbnail generation failed', [
                'source' => $sourcePath,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function failed(\Throwable $exception)
    {
        Log::error('ProcessImageUpload job permanently failed', [
            'listing_id' => $this->listingId,
            'listing_type' => $this->listingType,
            'image_paths' => $this->imagePaths,
            'error' => $exception->getMessage(),
            'attempts' => $this->attempts()
        ]);

        // Başarısız olan resimleri işaretle
        try {
            $listingClass = $this->listingType === 'sale' ? SaleListing::class : RepairListing::class;

            Image::where('imageable_id', $this->listingId)
                ->where('imageable_type', $listingClass)
                ->where('status', 'processing')
                ->update(['status' => 'failed']);
        } catch (\Exception $e) {
            Log::error('Failed to update image status after job failure', [
                'error' => $e->getMessage()
            ]);
        }

        // Geçici dosyaları temizle
        foreach ($this->imagePaths as $imagePath) {
            try {
                if (Storage::disk('local')->exists($imagePath)) {
                    Storage::disk('local')->delete($imagePath);
                }
            } catch (\Exception $e) {
                Log::warning('Could not clean up temp file', [
                    'path' => $imagePath,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
}
