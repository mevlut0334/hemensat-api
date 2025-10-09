<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RepairListingResource extends JsonResource
{
    /**
     * İlan temel verisini diziye dönüştürür.
     *
     * Bu resource tüm kullanıcılar için kullanılır.
     * Abone olmayan kullanıcılar sadece bu bilgileri görebilir.
     */
    public function toArray(Request $request): array
    {
        $user = $request->user();
        $isOwner = $user && $user->id === $this->user_id;
        $isSubscriber = $user && $user->isSubscriber();
        $canViewDetails = $isOwner || $isSubscriber;

        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'title' => $this->title,

            // Açıklama - Sadece ilk 100 karakter (abone değilse)
            'description' => $canViewDetails
                ? $this->description
                : $this->limitDescription($this->description, 100),
                'phone' => $canViewDetails ? $this->phone : null,

            // Cihaz Bilgisi
            'brand' => $this->whenLoaded('brand', fn() => new BrandResource($this->brand)),
            'model' => $this->whenLoaded('deviceModel', fn() => new DeviceModelResource($this->deviceModel)),
            'storage_capacity' => $this->whenLoaded('storageCapacity', fn() => new StorageCapacityResource($this->storageCapacity)),
            'purchase_source' => $this->whenLoaded('purchaseSource', fn() => new PurchaseSourceResource($this->purchaseSource)),

            // Konum (İl/İlçe)
            'province' => $this->whenLoaded('province', fn() => new ProvinceResource($this->province)),
            'district' => $this->whenLoaded('district', fn() => new DistrictResource($this->district)),

            // Durum ve Temel Bilgiler
            'status' => $this->status,
            'is_urgent' => (bool) $this->is_urgent,
            'preferred_repair_type' => $this->preferred_repair_type,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'published_at' => $this->published_at?->format('Y-m-d H:i:s'),

            // Görseller - Sadece ilk resim (abone değilse)
            'images' => $canViewDetails
                ? ImageResource::collection($this->whenLoaded('images'))
                : $this->getLimitedImages(),

            // Kullanıcı Bilgisi - Sadece abone/sahip görebilir
            'user' => $canViewDetails
                ? $this->whenLoaded('user', fn() => new UserResource($this->user))
                : null,

            // Yetkiler
            'permissions' => [
                'can_view_details' => $canViewDetails,
                'can_contact' => $canViewDetails,
                'is_owner' => $isOwner,
            ],

            // Temel İstatistikler
            'stats' => [
                'images_count' => $this->whenLoaded('images', fn() => $this->images->count(), 0),
            ],
        ];
    }

    /**
     * Açıklamayı kısaltır ve "..." ekler
     */
    private function limitDescription(?string $description, int $limit = 100): ?string
    {
        if (!$description || strlen($description) <= $limit) {
            return $description;
        }

        return substr($description, 0, $limit) . '...';
    }

    /**
     * Sadece ilk resmi döndürür (abone değilse)
     */
    private function getLimitedImages()
    {
        if (!$this->relationLoaded('images')) {
            return [];
        }

        $firstImage = $this->images->first();
        return $firstImage ? [new ImageResource($firstImage)] : [];
    }
}
